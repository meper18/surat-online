<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UpdateStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return Auth::check() && $user && ($user->hasRole('admin') || $user->hasRole('operator'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $permohonan = $this->route('permohonan');
        $currentStatus = $permohonan ? $permohonan->status : null;

        return [
            'status' => [
                'required',
                'string',
                Rule::in(['diajukan', 'diverifikasi', 'ditandatangani', 'selesai', 'ditolak']),
                function ($attribute, $value, $fail) use ($currentStatus) {
                    // Validate status transition logic
                    $validTransitions = [
                        'diajukan' => ['diverifikasi', 'ditolak'],
                        'diverifikasi' => ['ditandatangani', 'ditolak'],
                        'ditandatangani' => ['selesai', 'ditolak'],
                        'selesai' => [], // Final status, no transitions allowed
                        'ditolak' => ['diajukan'] // Can be resubmitted
                    ];

                    if ($currentStatus && isset($validTransitions[$currentStatus])) {
                        if (!in_array($value, $validTransitions[$currentStatus]) && $value !== $currentStatus) {
                            $fail('Transisi status dari ' . $currentStatus . ' ke ' . $value . ' tidak diizinkan.');
                        }
                    }
                }
            ],
            'keterangan_status' => [
                'nullable',
                'string',
                'max:1000',
                function ($attribute, $value, $fail) {
                    // Require explanation when rejecting
                    if ($this->input('status') === 'ditolak' && empty($value)) {
                        $fail('Keterangan wajib diisi ketika menolak permohonan.');
                    }
                }
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'keterangan_status.max' => 'Keterangan maksimal 1000 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'status' => 'status',
            'keterangan_status' => 'keterangan'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new ValidationException($validator, redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Terdapat kesalahan dalam update status. Silakan periksa kembali.'));
    }
}
