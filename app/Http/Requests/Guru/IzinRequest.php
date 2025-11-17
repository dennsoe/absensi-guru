<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class IzinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'guru';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'jenis' => [
                'required',
                Rule::in(['sakit', 'izin', 'cuti']),
            ],
            'tanggal_mulai' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'alasan' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
            'file_pendukung' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120', // 5MB
            ],
            'guru_pengganti_id' => [
                'nullable',
                'exists:guru,id',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'jenis' => 'Jenis Izin',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'alasan' => 'Alasan',
            'file_pendukung' => 'File Pendukung',
            'guru_pengganti_id' => 'Guru Pengganti',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'jenis.required' => ':attribute wajib dipilih',
            'jenis.in' => ':attribute tidak valid',
            'tanggal_mulai.required' => ':attribute wajib diisi',
            'tanggal_mulai.after_or_equal' => ':attribute tidak boleh kurang dari hari ini',
            'tanggal_selesai.required' => ':attribute wajib diisi',
            'tanggal_selesai.after_or_equal' => ':attribute harus sama atau setelah tanggal mulai',
            'alasan.required' => ':attribute wajib diisi',
            'alasan.min' => ':attribute minimal :min karakter',
            'alasan.max' => ':attribute maksimal :max karakter',
            'file_pendukung.mimes' => ':attribute harus format PDF, JPG, JPEG, atau PNG',
            'file_pendukung.max' => ':attribute maksimal 5MB',
            'guru_pengganti_id.exists' => 'Guru pengganti tidak ditemukan',
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
