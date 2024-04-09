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
 (function() {
  $(document).ready(function() {
    var clear_selections, mailed, move, potentials, simplesms_changer, selectors;
    potentials = "#from_users";
    mailed = "#send_users";
    selectors = [potentials, mailed];
	// RT
	var disable_add = true;
	var disable_remove = true;
	var cancelflag = false;

    clear_selections = function() {
      var clear;
      clear = function(index, selector) {
        return $(selector).children(":selected").attr("selected", false);
      };
      return $(selectors).each(clear);
    };
    simplesms_changer = function() {

      clear_selections();
      return $("#groups").children(":selected").each(function(outer, group) {
        return $(selectors).each(function(inner, selector) {
          return $(selector).children("*").each(function(select, option) {
            var groups, in_list, selected, values;
            values = $(option).val().split(' ');

            groups = values[1].split(',');
            in_list = function(obj, list) {
              var filter;
              filter = function() {
                return String(this) === obj;
              };
              return $(list).filter(filter).length > 0;
            };
            selected = true;
            if (in_list($(group).val(), groups)) {
              return $(option).attr('selected', selected);
            }
          });
        });
      });
    };
    move = function(from, to, filter) {
      return function() {
        $(from).children(filter).appendTo(to);
        return $(from).children(filter).remove();
      };
    };

	var toggle_button = function(el, flag, status)
	{
		flag = status; // update flag;

		if($(el).prop('disabled') != flag)
		{
			$(el).prop('disabled', flag);
		}
		return;
	};

	$("#from_users").click(function() {
		if($("#from_users").is(':empty'))
		{
			toggle_button("#add_button", disable_add, true);
			toggle_button("#add_button", disable_add, true);
			return;
		}

		toggle_button("#add_button", disable_add, false);
		return;
	});

	$("#send_users").click(function() {
		if($("#send_users").is(':empty'))
		{
			toggle_button("#remove_button", disable_remove, true);
			return;
		}

		toggle_button("#remove_button", disable_remove, false);
		return;
	});

	$("#groups").click(function() {
		if($("#from_users").is(':empty'))
		{
			toggle_button("#add_button", disable_add, true);
		}
		else
		{
			toggle_button("#add_button", disable_add, false);
		}

		if($("#send_users").is(':empty'))
		{
			toggle_button("#remove_button", disable_remove, true);
		}
		else
		{
			toggle_button("#remove_button", disable_remove, false);
		}

		return;
	});

    $("#groups").change(simplesms_changer);
    $("#add_button").click(move(potentials, mailed, ':selected'));
    $("#add_all").click(move(potentials, mailed, '*'));
    $("#remove_button").click(move(mailed, potentials, ':selected'));
    $("#remove_all").click(move(mailed, potentials, '*'));

	$("#id_cancel").click(function(){
		cancelflag = true;
	});

    return $(".mform").submit(function() {
      var ids, mapper;
	  // RT
	  if(cancelflag)
	  {
		return true;
	  }
      mapper = function(index, elem) {
        return $(elem).val().split(' ')[0];
      };
      ids = $(mailed).children("*").map(mapper).get().join(',');
      if (ids === '') {
        return false;
      } else {
        $("input[name=sendto]").val(ids);
        return true;
      }
    });
  });

}).call(this);

});
