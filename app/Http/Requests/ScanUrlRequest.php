<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanUrlRequest extends FormRequest
{
    /**
     * Tentukan apakah request diizinkan.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi input.
     */
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'string',
                'max:2048',
                'url:http,https',
            ],
        ];
    }

    /**
     * Pesan validasi khusus.
     */
    public function messages(): array
    {
        return [
            'url.required' => 'URL wajib diisi.',
            'url.string' => 'URL harus berupa teks.',
            'url.max' => 'URL tidak boleh lebih dari 2048 karakter.',
            'url.url' => 'Masukkan URL yang valid dengan protokol HTTP atau HTTPS.',
        ];
    }

    /**
     * Nama atribut untuk pesan validasi.
     */
    public function attributes(): array
    {
        return [
            'url' => 'URL',
        ];
    }
}
