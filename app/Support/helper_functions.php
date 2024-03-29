<?php

function obfuscate_email(string $email): string
{
    $splitted = explode('@', $email);

    $firstPart       = $splitted[0];
    $qty             = floor(strlen($firstPart) * 0.75);
    $remaining       = strlen($firstPart) - $qty;
    $maskedFirstPart = substr($firstPart, 0, $remaining) . str_repeat("*", $qty);

    $secondPart       = $splitted[1];
    $qty              = floor(strlen($secondPart) * 0.75);
    $remaining        = strlen($secondPart) - $qty;
    $maskedSecondPart = str_repeat("*", $qty) . substr($secondPart, $remaining * -1, $remaining);

    return $maskedFirstPart . '@' . $maskedSecondPart;
}
