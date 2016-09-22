<?php
/*
 * LICENSE : Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace syruppay\jose;
use syruppay\jose\jwa\Jwa;

/**
 * 지원하는 JOSE enc 알고리즘 여부를 판단하는 class
 *
 * @package syruppay\jose
 */
class JoseSupportEncryption
{
    /**
     * @var array JWE: A128CBC-HS256
     */
    private static $jweSupportEnc = array(
        Jwa::A128CBC_HS256
    );

    /**
     * 입력한 enc가 JWE 지원하는 알고리즘인지 확인을 한다.
     *
     * @param $enc string
     * @return bool
     */
    public static function isSupported($enc)
    {
        return in_array($enc, self::$jweSupportEnc);
    }
}