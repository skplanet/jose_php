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

namespace com\skplanet\jose;

use com\skplanet\jose\exception\UnSupportedJoseAlgorithmException;
use com\skplanet\jose\jwa\Jwa;
use com\skplanet\jose\util\Base64UrlSafeEncoder;

class JoseHeader
{
    private $header = array();

    public function __construct()
    {
        $argNum = func_num_args();
        $arg = func_get_args();
        if ($argNum == 1 and is_array($arg[0]))
        {
            foreach($arg[0] as $key => $value)
            {
                $this->setHeader($key, $value);
            }
        }
        else if ($argNum != 0)
        {
            throw new \InvalidArgumentException('Usage : new JoseHeader(array(JoseHeaderSpec => $value)');
        }
    }

    public function setAlg($alg)
    {
        if (!JoseSupportAlgorithm::isSupported($alg))
        {
            throw new UnSupportedJoseAlgorithmException("unknown header 'alg' value : ".$alg);
        }

        $this->header[JoseHeaderSpec::ALG] = $alg;
        return $this;
    }

    public function getAlg()
    {
        return $this->header[JoseHeaderSpec::ALG];
    }

    public function setEnc($enc)
    {
        if (!JoseSupportEncryption::isSupported($enc))
        {
            throw new UnSupportedJoseAlgorithmException("unknown header 'enc' value : ".$enc);
        }

        $this->header[JoseHeaderSpec::ENC] = $enc;
        return $this;
    }

    public function getEnc()
    {
        return $this->header[JoseHeaderSpec::ENC];
    }

    public function setKid($kid)
    {
        $this->header[JoseHeaderSpec::KID] = $kid;
        return $this;
    }

    public function getKid()
    {
        return $this->header[JoseHeaderSpec::KID];
    }

    public function setHeader($key, $value)
    {
        $this->header[$key] = $value;
        return $this;
    }

    public function getHeader($key)
    {
        return $this->header[$key];
    }

    public function __toString()
    {
        return json_encode($this->header);
    }

    public function serialize()
    {
        return Base64UrlSafeEncoder::encode($this->__toString());
    }

    public function deserialize($src)
    {
        list($headerToken, $rest) = array_pad(explode(".", $src, 2), 2, null);
        $this->header = json_decode(Base64UrlSafeEncoder::decode($headerToken), true);
    }

    public function getJoseMethod()
    {
        if (JoseSupportAlgorithm::isJWESupported($this->getAlg()))
        {
            return JoseMethod::JWE;
        }
        else if (JoseSupportAlgorithm::isJWSSupported($this->getAlg()))
        {
            return JoseMethod::JWS;
        }
        else
        {
            throw new UnSupportedJoseAlgorithmException($this->getAlg().' is not supported.');
        }
    }
}

class JoseSupportAlgorithm
{
    private static $jweSupportAlg = array(
        Jwa::A128KW,
        Jwa::A256KW
    );

    private static $jwsSupportAlg = array(
        Jwa::HS256
    );

    public static function isSupported($alg)
    {
        return self::isJWESupported($alg) or self::isJWSSupported($alg);
    }

    public static function isJWESupported($alg)
    {
        return in_array($alg, self::$jweSupportAlg);
    }

    public static function isJWSSupported($alg)
    {
        return in_array($alg, self::$jwsSupportAlg);
    }
}

class JoseSupportEncryption
{
    private static $jweSupportEnc = array(
        Jwa::A128CBC_HS256
    );

    public static function isSupported($enc)
    {
        return in_array($enc, self::$jweSupportEnc);
    }
}

