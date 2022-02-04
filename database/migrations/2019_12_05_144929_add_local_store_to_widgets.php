<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocalStoreToWidgets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("INSERT INTO `widgets_categories` (`id`, `title`, `rank`) VALUES ('7', 'Local Store', '1');");
		DB::statement("
			INSERT INTO `widgets` (`id`, `title`, `type`, `content`, `rank`) VALUES 
				('13', 'Rosemont store', '2', '‡‡<p class=\"leading-relaxed\">Hope&rsquo;s Cookies have consistently won awards and accolades over the years including <strong>\"Best of Philly\" 6<em>&nbsp;times</em></strong>, and more! It&rsquo;s easy to understand why: our generous sized cookies are made with ingredients that are 100% natural, and 100% delicious. We use no chemicals, no artificial colors or flavors, and no preservatives. Our retail store, located right on the Main Line in Rosemont, PA is a local tradition. For nearly two decades we&rsquo;ve served up the best gourmet, home style cookies, bakery goods, muffins, and dairy bar treats like ice cream, Boardwalk Custard, yogurt and more, in a fun and family-friendly environment.</p>‡‡‡', '1'),
				('14', 'Hours', '2', '‡‡<div>\r\n<p><span class=\"text-pink-500 font-roboto\">Phone:</span><br /> <a href=\"tel:6105274488\">610.527.4488</a></p>\r\n<p><span class=\"text-pink-500 font-roboto\">Fax:</span><br /> 610.527.4673</p>\r\n</div>\r\n<div>\r\n<div class=\"mb-3\"><span class=\"text-pink-500 font-roboto\">Hours:</span><br /> <span class=\"font-bold\">Monday to Thursday</span><br /> 8:00 AM - 10:00 PM</div>\r\n<div class=\"mb-3\"><span class=\"font-bold\">Friday - Saturday</span><br /> 8:00 AM-11:00 PM</div>\r\n<div><span class=\"font-bold\">Sunday</span><br /> 10:00 AM-10:00 PM</div>\r\n</div>‡‡‡', '2'),
				('15', 'Call Ahead', '2', '‡‡<h3 class=\"text-pink text-3xl font-trebuchet mb-3\">Call Ahead and Pick Up In Store!</h3>\r\n<p>In a hurry? No problem!</p>\r\n<p>Call-in or fax your order to our Rosemont store (phone numbers listed below) and we\'ll have it ready for you to pick up when you get here!</p>\r\n<p>Whether you\'re picking up a holiday gift or treating yourself, we\'ve got you covered!</p>\r\n<p><span class=\"font-bold\">Phone:</span> <a href=\"tel:6105274488\">610.527.4488</a></p>\r\n<p class=\"pb-0\"><span class=\"font-bold\">Fax:</span> 610.527.4673</p>‡‡‡', '3');
		");
		DB::statement("
			INSERT INTO `widgets_in_categories` (`id`, `category_id`, `widget_id`, `rank`) VALUES 
				('15', '7', '13', '1'),
				('16', '7', '14', '2'),
				('17', '7', '15', '3');
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
