<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateGuruRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $guruId = $this->route('guru') ?? $this->route('id');

        return [
            'nip' => [
                'required',
                'string',
                'digits:18',
                Rule::unique('guru', 'nip')->ignore($guruId),
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('guru', 'email')->ignore($guruId),
            ],
            'no_hp' => [
                'required',
                'string',
                'regex:/^(\+62|62|0)[0-9]{9,12}$/',
                Rule::unique('guru', 'no_hp')->ignore($guruId),
            ],
            'jenis_kelamin' => [
                'required',
                Rule::in(['L', 'P']),
            ],
            'tempat_lahir' => [
                'required',
                'string',
                'max:100',
            ],
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
            ],
            'alamat' => [
                'required',
                'string',
                'max:500',
            ],
            'status_kepegawaian' => [
                'required',
                Rule::in(['PNS', 'PPPK', 'GTY', 'GTT']),
            ],
            'tanggal_masuk' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:2048',
            ],
            'is_aktif' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nip' => 'NIP',
            'nama' => 'Nama Lengkap',
            'email' => 'Email',
            'no_hp' => 'No HP',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'status_kepegawaian' => 'Status Kepegawaian',
            'tanggal_masuk' => 'Tanggal Masuk',
            'foto' => 'Foto',
            'is_aktif' => 'Status Aktif',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nip.required' => ':attribute wajib diisi',
            'nip.digits' => ':attribute harus 18 digit',
            'nip.unique' => ':attribute sudah terdaftar',
            'nama.required' => ':attribute wajib diisi',
            'email.required' => ':attribute wajib diisi',
            'email.email' => ':attribute tidak valid',
            'email.unique' => ':attribute sudah terdaftar',
            'no_hp.required' => ':attribute wajib diisi',
            'no_hp.regex' => ':attribute tidak valid',
            'no_hp.unique' => ':attribute sudah terdaftar',
            'jenis_kelamin.required' => ':attribute wajib dipilih',
            'tempat_lahir.required' => ':attribute wajib diisi',
            'tanggal_lahir.required' => ':attribute wajib diisi',
            'tanggal_lahir.before' => ':attribute harus sebelum hari ini',
            'alamat.required' => ':attribute wajib diisi',
            'status_kepegawaian.required' => ':attribute wajib dipilih',
            'tanggal_masuk.required' => ':attribute wajib diisi',
            'tanggal_masuk.before_or_equal' => ':attribute tidak boleh lebih dari hari ini',
            'foto.image' => ':attribute harus berupa gambar',
            'foto.mimes' => ':attribute harus format JPEG, JPG, atau PNG',
            'foto.max' => ':attribute maksimal 2MB',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses',
                ], 403)
            );
        }

        parent::failedAuthorization();
    }
}
