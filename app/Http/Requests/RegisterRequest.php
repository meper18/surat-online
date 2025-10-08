<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'nik' => [
                'required',
                'string',
                'size:16',
                'unique:users,nik',
                'regex:/^[0-9]{16}$/'
            ],
            'tempat_lahir' => [
                'required',
                'string',
                'max:255'
            ],
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01'
            ],
            'agama' => [
                'required',
                'string',
                'max:255',
                'in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu'
            ],
            'pekerjaan' => [
                'required',
                'string',
                'max:255'
            ],
            'lingkungan' => [
                'required',
                'integer',
                'min:1',
                'max:50'
            ],
            'alamat' => [
                'required',
                'string',
                'max:500'
            ],
            'no_hp' => [
                'required',
                'string',
                'max:15',
                'regex:/^(\+62|62|0)[0-9]{9,13}$/'
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
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nik.regex' => 'NIK hanya boleh berisi angka.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tanggal_lahir.after' => 'Tanggal lahir tidak valid.',
            'agama.required' => 'Agama wajib dipilih.',
            'agama.in' => 'Pilihan agama tidak valid.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'lingkungan.required' => 'Lingkungan wajib diisi.',
            'lingkungan.min' => 'Lingkungan minimal 1.',
            'lingkungan.max' => 'Lingkungan maksimal 50.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Format nomor HP tidak valid.',
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
            'name' => 'nama lengkap',
            'email' => 'email',
            'password' => 'password',
            'nik' => 'NIK',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'agama' => 'agama',
            'pekerjaan' => 'pekerjaan',
            'lingkungan' => 'lingkungan',
            'alamat' => 'alamat',
            'no_hp' => 'nomor HP'
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
            ->withInput($this->except('password', 'password_confirmation'))
            ->with('error', 'Terdapat kesalahan dalam registrasi. Silakan periksa kembali.'));
    }
}
