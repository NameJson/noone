<?php
/**
 * @Author:  LiuHao
 * @Date:  2017/11/18 下午4:35
 * @Email:  lh@btctrade.com
 * @File:  Captcha.php
 * @Desc:  ...
 */
namespace Helper;
/**
 * Class  Captcha
 *
 * @package  Helper
 * @author  liuhao <lh@btctrade.com>
 * 安全验证码
 * 安全的验证码要：验证码文字旋转，使用不同字体，可加干扰码、可加干扰线
 */
class Captcha
{
	# 验证码字体大小(px)
	static $fontSize = 25;
	# 验证码图片宽
	static $imageH = 0;
	# 验证码图片长
	static $imageL = 0;

	private static $_codeSet = '346789ABCDEFGHJKLMNPQRTUVWXY';
	# 验证码图片实例
	private static $_image = null;
	# 验证码字体颜色
	private static $_color = null;

	/**
	 * 显示验证码
	 */
	static function show()
	{
		# 图片宽(px)
		self::$imageL || self::$imageL = 5 * self::$fontSize;
		# 图片高(px)
		self::$imageH || self::$imageH = 1.4 * self::$fontSize;
		# 建立一幅 self::$imageL x self::$imageH 的图像
		self::$_image = imagecreate(self::$imageL, self::$imageH);
		# 设置背景
		imagecolorallocate(self::$_image, 243, 251, 254);
		# 验证码字体随机颜色
		self::$_color = imagecolorallocate(self::$_image, mt_rand(1, 120), mt_rand(1, 120), mt_rand(1, 120));
		# 绘杂点
		for ($i = 0; $i < 10; $i++) {
			$noiseColor = imagecolorallocate(self::$_image, mt_rand(150, 225), mt_rand(150, 225), mt_rand(150, 225));
			for ($j = 0; $j < 5; $j++) imagestring(self::$_image, 5, mt_rand(-10, self::$imageL), mt_rand(-10, self::$imageH), self::$_codeSet[mt_rand(0, 27)], $noiseColor);
		}
		# 干扰线
		$py = 0;
		# 曲线前部分
		$A = mt_rand(1, self::$imageH / 2); # 振幅
		$b = mt_rand(-self::$imageH / 4, self::$imageH / 4); # Y轴方向偏移量
		$f = mt_rand(-self::$imageH / 4, self::$imageH / 4); # X轴方向偏移量
		$T = mt_rand(self::$imageH, self::$imageL * 2); # 周期
		$w = (2 * M_PI) / $T;
		# 曲线横坐标起始位置
		$px1 = 0;
		# 曲线横坐标结束位置
		$px2 = mt_rand(self::$imageL / 2, self::$imageL * 0.8);
		for ($px = $px1; $px <= $px2; $px = $px + 0.9) {
			if ($w != 0) {
				# y = Asin(ωx+φ) + b
				$py = $A * sin($w * $px + $f) + $b + self::$imageH / 2;
				$i = (int)(self::$fontSize / 5);
				while ($i > 0) {
					imagesetpixel(self::$_image, $px, $py + $i, self::$_color);
					--$i;
				}
			}
		}
		# 曲线后部分
		$A = mt_rand(1, self::$imageH / 2); # 振幅
		$f = mt_rand(-self::$imageH / 4, self::$imageH / 4); # X轴方向偏移量
		$T = mt_rand(self::$imageH, self::$imageL * 2); # 周期
		$w = (2 * M_PI) / $T;
		$b = $py - $A * sin($w * $px + $f) - self::$imageH / 2;
		$px1 = $px2;
		$px2 = self::$imageL;
		for ($px = $px1; $px <= $px2; $px = $px + 0.9) {
			if ($w != 0) {
				# y = Asin(ωx+φ) + b
				$py = $A * sin($w * $px + $f) + $b + self::$imageH / 2;
				$i = (int)(self::$fontSize / 5);
				while ($i > 0) {
					imagesetpixel(self::$_image, $px, $py + $i, self::$_color);
					--$i;
				}
			}
		}
		# 绘验证码
		# 验证码第N个字符的左边距
		$codeNX = 10;
		$ttf = APPLICATION_PATH . '/doc/font/captcha/' . rand(1, 7) . '.ttf';
		$code = array(self::$_codeSet[mt_rand(0, 27)]);
		imagettftext(self::$_image, self::$fontSize, mt_rand(-40, 40), $codeNX, self::$fontSize * 1.15, self::$_color, $ttf, $code[0]);
		for ($i = 1; $i < 4; ++$i) {
			$code[$i] = self::$_codeSet[mt_rand(0, 27)];
			$codeNX += mt_rand(self::$fontSize, self::$fontSize * 1.2);
			# 写一个验证码字符
			imagettftext(self::$_image, self::$fontSize, mt_rand(-40, 40), $codeNX, self::$fontSize * 1.15, self::$_color, $ttf, $code[$i]);
		}
		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header("content-type: image/png");
		# 输出图像
		imagepng(self::$_image);
		imagedestroy(self::$_image);
		return join('', $code);
	}
}