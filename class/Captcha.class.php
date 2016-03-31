<?
/**
 * #####################################################################################################################
 * Copyright (C) 2016   Thomas Weber
 * #####################################################################################################################
 * This file is part of AccessV2.
 *
 * AccessV2 is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 * AccessV2 is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *     along with AccessV2.  If not, see <http://www.gnu.org/licenses/>.
 * #####################################################################################################################
 **/
?>

<?

/**
 * Class Captcha
 * @desc output captcha
 */
class Captcha
{
    public $sCaptcha;

    public function __construct()
    {
        $this->sCaptcha = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
    }

    public function getCaptcha()
    {
        // header("Content-type: image/png");
        $im = @ImageCreate(230, 40);
        $background_color = ImageColorAllocate($im, 255, 255, 255);

        $color_red = ImageColorAllocate($im, 255, 0, 0);
        $color_green = ImageColorAllocate($im, 0, 255, 0);
        $color_blue = ImageColorAllocate($im, 0, 0, 255);
        $color_white = ImageColorAllocate($im, 0, 0, 0);

        $iRand = rand(0, 2);
        if ($iRand == 0) {
            $captcha_color = $color_red;
            $color_arr[0] = $color_green;
            $color_arr[1] = $color_blue;
            ImageString($im, 3, 125, 13, "captcha: red", $color_white);
        } else if ($iRand == 1) {
            $captcha_color = $color_green;
            $color_arr[0] = $color_red;
            $color_arr[1] = $color_blue;
            ImageString($im, 3, 125, 13, "captcha: green", $color_white);
        } else if ($iRand == 2) {
            $captcha_color = $color_blue;
            $color_arr[0] = $color_red;
            $color_arr[1] = $color_green;
            ImageString($im, 3, 125, 13, "captcha: blue", $color_white);
        }

        for ($i = 0; $i < strlen($this->sCaptcha); $i++) {
            $realPos = rand(0, 2);
            $iFlip = rand(0, 1);
            $tempx = 10 + $i * 20;
            if ($realPos == 0) {
                ImageString($im, 5, $tempx, 0, $this->sCaptcha[$i], $captcha_color);
                ImageString($im, 5, $tempx, 12, substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1), $color_arr[$iFlip]);
                ImageString($im, 5, $tempx, 24, substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1), $color_arr[!$iFlip]);
            } else if ($realPos == 1) {
                ImageString($im, 5, $tempx, 0, substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1), $color_arr[$iFlip]);
                ImageString($im, 5, $tempx, 12, $this->sCaptcha[$i], $captcha_color);
                ImageString($im, 5, $tempx, 24, substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1), $color_arr[!$iFlip]);
            } else {
                ImageString($im, 5, $tempx, 0, substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1), $color_arr[$iFlip]);
                ImageString($im, 5, $tempx, 12, substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1), $color_arr[!$iFlip]);
                ImageString($im, 5, $tempx, 24, $this->sCaptcha[$i], $captcha_color);
            }
        }

        ob_start();
        imagepng($im);
        $img_data = ob_get_contents();
        ob_end_clean();
        // echo '<img class="img-responsive center-block" src="data:image/png;base64,' . base64_encode($img_data) . '"/>';
        echo base64_encode($img_data);
    }

    public function getCaptchaHash()
    {
        print md5($this->sCaptcha);
    }

    static public function checkCaptcha($captchaInput, $captchaHash)
    {
        return (md5($captchaInput) == $captchaHash);
    }
}

?>