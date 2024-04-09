// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Client-side JavaScript for User selection interface.
 * @copyright 2014 Rajneel Totaram
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package block_simplesms
 */
 
require(['jquery'], function($) {

//Function to the keyup event on the textarea
 	$('#id_message').keyup(function(){
		limit = 153;

		var text  = $('#id_message').val();
		if(text.length > limit)
			text = $('#id_message').val(text.substr(0,limit));
		text  = $('#id_message').val();
		var chars = limit - text.length;
		$('#charlimitinfo').html(chars);
 	});

	$('#id_send').click(function(){
		$('#id_send').hide();
 	});
});
