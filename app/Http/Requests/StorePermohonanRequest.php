<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StorePermohonanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return Auth::check() && $user && $user->hasRole('warga');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis_surat_id' => [
                'required',
                'integer',
                'exists:jenis_surats,id'
            ],
            'keperluan' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            'tanggal_surat_pernyataan' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:1000'
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
            'jenis_surat_id.required' => 'Jenis surat harus dipilih.',
            'jenis_surat_id.exists' => 'Jenis surat yang dipilih tidak valid.',
            'keperluan.required' => 'Keperluan harus diisi.',
            'keperluan.min' => 'Keperluan minimal harus 10 karakter.',
            'keperluan.max' => 'Keperluan maksimal 500 karakter.',
            'tanggal_surat_pernyataan.required' => 'Tanggal surat pernyataan harus diisi.',
            'tanggal_surat_pernyataan.date' => 'Format tanggal tidak valid.',
            'tanggal_surat_pernyataan.after_or_equal' => 'Tanggal surat pernyataan tidak boleh kurang dari hari ini.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.'
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
            'jenis_surat_id' => 'jenis surat',
            'keperluan' => 'keperluan',
            'tanggal_surat_pernyataan' => 'tanggal surat pernyataan',
            'catatan' => 'catatan'
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
        $errors = $validator->errors();
        $errorMessages = [];
        
        foreach ($errors->all() as $error) {
            $errorMessages[] = $error;
        }
        
        $errorMessage = count($errorMessages) > 1 
            ? 'Terdapat beberapa kesalahan dalam pengisian form: ' . implode(', ', $errorMessages)
            : 'Terdapat kesalahan dalam pengisian form: ' . $errorMessages[0];
        
        throw new ValidationException($validator, redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', $errorMessage));
    }
}
