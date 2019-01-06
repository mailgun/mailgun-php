<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Lists;

/**
 * This class is used for creating a unique hash for
 * mailing list subscription double-opt in requests.
 *
 * @see https://github.com/mailgun/mailgun-php/blob/master/src/Mailgun/Lists/README.md
 */
class OptInHandler
{
    /**
     * @param string $mailingList
     * @param string $secretAppId
     * @param string $recipientAddress
     *
     * @return string
     */
    public function generateHash($mailingList, $secretAppId, $recipientAddress)
    {
        $innerPayload = ['r' => $recipientAddress, 'l' => $mailingList];
        $encodedInnerPayload = base64_encode(json_encode($innerPayload));

        $innerHash = hash_hmac('sha1', $encodedInnerPayload, $secretAppId);
        $outerPayload = ['h' => $innerHash, 'p' => $encodedInnerPayload];

        return urlencode(base64_encode(json_encode($outerPayload)));
    }

    /**
     * @param string $secretAppId
     * @param string $uniqueHash
     *
     * @return array|bool
     */
    public function validateHash($secretAppId, $uniqueHash)
    {
        $decodedOuterPayload = json_decode(base64_decode(urldecode($uniqueHash)), true);

        $decodedHash = $decodedOuterPayload['h'];
        $innerPayload = $decodedOuterPayload['p'];

        $decodedInnerPayload = json_decode(base64_decode($innerPayload), true);
        $computedInnerHash = hash_hmac('sha1', $innerPayload, $secretAppId);

        if ($computedInnerHash == $decodedHash) {
            return ['recipientAddress' => $decodedInnerPayload['r'], 'mailingList' => $decodedInnerPayload['l']];
        }

        return false;
    }
}
