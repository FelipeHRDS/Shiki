<?php

class Formatter {

    static function formatCnpj($cnpj) {
        $cnpj = (string)$cnpj;
        $formattedCnpj = sprintf('%014d', $cnpj);
        
        $formattedCnpj = substr_replace($formattedCnpj, '.', 2, 0);
        $formattedCnpj = substr_replace($formattedCnpj, '.', 6, 0);
        $formattedCnpj = substr_replace($formattedCnpj, '/', -6, 0);
        $formattedCnpj = substr_replace($formattedCnpj, '-', -2, 0);

        return $formattedCnpj;
    }

    static function formatUserSystemCode($userSystemCode) {
        $userSystemCodeStr = (string) $userSystemCode;
        $userSystemCodeLastDigit = substr($userSystemCodeStr, -1);
        $userSystemCodeStr = substr($userSystemCodeStr, 0, -1);
        $formattedUserSystemCode = "$userSystemCodeStr-$userSystemCodeLastDigit";
        return $formattedUserSystemCode;
    }
}