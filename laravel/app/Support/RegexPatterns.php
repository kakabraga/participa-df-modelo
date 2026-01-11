<?php

namespace App\Support;

class RegexPatterns
{
    public const CPF = '/\b\d{3}\.\d{3}\.\d{3}-\d{2}\b/';
    public const CNPJ = '/\b\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}\b/';
    public const EMAIL = '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i';
    public const TELEFONE = '/\b\(?\d{2}\)?\s?\d{4,5}-?\d{4}\b/';
}
