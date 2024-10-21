<?php


namespace App\Classes;

class DocumentNumberGenerator
{
    private $prefix;
    private $dateFormat;

    public function __construct(string $prefix, string $dateFormat = 'Ymd')
    {
        $this->prefix = $prefix;
        $this->dateFormat = $dateFormat;
    }

    public function generate($lastDocument = null): string
    {
        $today = date($this->dateFormat);

        if ($lastDocument && $lastDocument->number) {
            $lastDocumentDate = substr($lastDocument->number, strlen($this->prefix) + 1, strlen($today));
            $lastNumber = (int) substr($lastDocument->number, -4);

            if ($lastDocumentDate === $today) {
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
        } else {
            $newNumber = 1;
        }

        return $this->prefix . '-' . $today . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}