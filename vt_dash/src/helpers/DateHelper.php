<?php

namespace src\helpers;

class DateHelper
{
    /**
     * Converte uma data ISO 8601 para um formato amigável em português.
     *
     * @param string|null $isoDate Data no formato ISO 8601 (ou null)
     * @return string Data formatada ou "Sem registro"
     */
    public static function formatDateTime(?string $isoDate): string
    {
        if (empty($isoDate)) {
            return "Sem registro";
        }

        try {
            $date = new \DateTime(substr($isoDate, 0, 19), new \DateTimeZone('UTC'));
            $date->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
        } catch (\Exception $e) {
            return "Data inválida";
        }

        $months = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
        $day   = $date->format('d');
        $month = $months[(int) $date->format('m') - 1];
        $year  = $date->format('Y');
        $hour  = (int) $date->format('H');
        $minute = (int) $date->format('i');

        return "{$day} de {$month}. de {$year} às {$hour}h e {$minute}min";
    }

    /**
     * Formata data no padrão dd/mm/yyyy H:i (São Paulo timezone).
     *
     * @param string|null $isoDate
     * @return string
     */
    public static function formatShort(?string $isoDate): string
    {
        if (empty($isoDate)) {
            return "Sem registro";
        }

        try {
            $date = new \DateTime($isoDate, new \DateTimeZone('UTC'));
            $date->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
            return $date->format('d/m/Y H:i');
        } catch (\Exception $e) {
            return "Data inválida";
        }
    }
}
