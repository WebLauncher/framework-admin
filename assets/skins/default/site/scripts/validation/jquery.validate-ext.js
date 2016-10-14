function __server_side(rule, value, element, params, validator) {
	pars = '';
	if (params.length > 0)
		pars = '|' + params.join('|');
	pars = rule + pars;

	if (validator.optional(element))
		return "dependency-mismatch";

	var previous = validator.previousValue(element);
	if (!validator.settings.messages[element.name])
		validator.settings.messages[element.name] = {};
	previous.originalMessage = validator.settings.messages[element.name][rule];
	validator.settings.messages[element.name][rule] = previous.message;

	params = typeof params == "string" && {
		url : params
	} || params;

	if (validator.pending[element.name]) {
		return "pending";
	}
	if (previous.old === value) {
		return previous.valid;
	}

	previous.old = value;
	validator.startRequest(element);
	var data = {
		a : 'validate',
		rule : pars,
		value : value
	};
	jQuery.ajax(jQuery.extend(true, {
		url : root_current,
		type: "POST",
		mode : "abort",
		port : "validate" + element.name,
		dataType : "json",
		data : data,
		success : function(response) {
			validator.settings.messages[element.name][rule] = previous.originalMessage;
			if(response==1)response=true;
			else response=false;
			var valid = response === true;
			if (valid) {
				var submitted = validator.formSubmitted;
				validator.prepareElement(element);
				validator.formSubmitted = submitted;
				validator.successList.push(element);
				validator.showErrors();
			} else {
				var errors = {};
				var message = response || validator.defaultMessage(element, rule);
				errors[element.name] = previous.message = jQuery.isFunction(message) ? message(value) : message;
				validator.showErrors(errors);
			}
			previous.valid = valid;
			validator.stopRequest(element, valid);
		}
	}, params));
	return "pending";
}

/**
 * Compare method
 *
 */
jQuery.validator.addMethod("compare", function(value, element, params) {
	ok = 0;
	compare_val = params[1];
	if (jQuery("[name='" + params[1] + "']").length)
		compare_val = jQuery("[name='" + params[1] + "']").val();
	switch(params[0]) {
		case "<":
			ok = value < compare_val;
			break;
		case "=":
			ok = value == compare_val;
			break;
		case ">":
			ok = value > compare_val;
			break;
		case "!=":
			ok = value != compare_val;
			break;
	}
	return this.optional(element) || ok;
}, "Comparison condition does not match the values!");
/**
 * Compare method
 *
 */
jQuery.validator.addMethod("comparetxt", function(value, element, params) {
	ok = 0;
	compare_val = params[1];
	switch(params[0]) {
		case "<":
			ok = value < compare_val;
			break;
		case "=":
			ok = value == compare_val;
			break;
		case ">":
			ok = value > compare_val;
			break;
		case "!=":
			ok = value != compare_val;
			break;
	}
	return this.optional(element) || ok;
}, "Comparison condition does not match the values!");

/**
 * Interval method {interval|[start_len]|[end_len]}
 */
jQuery.validator.addMethod("interval", function(value, element, param) {
	var length = this.getLength(jQuery.trim(value), element);
	return this.optional(element) || (length >= param[0] && length <= param[1] );
}, jQuery.format("Please enter a value between {0} and {1} characters long."));

/**
 * Intervalnr method {intervalnr|[start_nr]|[end_nr]}
 */
jQuery.validator.addMethod("intervalnr", function(value, element, param) {
	return this.optional(element) || (value >= param[0] && value <= param[1] );
}, jQuery.format("Please enter a value between {0} and {1}."));

/**
 * Intervalnr method {username}
 */
jQuery.validator.addMethod("username", function(value, element) {
	return this.optional(element) || /^[a-z][\da-z_\.]{4,64}[a-z\d]$/.test(value);
}, jQuery.format("Please enter a valid username between 6-64 characters long, no uppercase letters and starting with a letter."));

/**
 * Intervalnr method {username_full}
 */
jQuery.validator.addMethod("username_full", function(value, element) {
	return this.optional(element) || /^[\da-zA-Z][\da-zA-Z_\.\,\-]{4,64}[\da-zA-Z]$/.test(value);
}, jQuery.format("Please enter a valid username between 6-64 characters long, no uppercase letters and starting with a letter."));


/**
 * Percent method {percent}
 */
jQuery.validator.addMethod("percent", function(value, element) {
	return this.optional(element) || (value >= 0 && value <= 100 );
}, jQuery.format("Please enter a valid percent between 0 and 100."));

/**
 * Firstname method {firstname}
 */
jQuery.validator.addMethod("firstname", function(value, element) {
	return this.optional(element) || /^[A-Z][a-zA-Z \-]{1,64}[a-z]$/.test(value);
}, jQuery.format("Please enter a valid username between 2-64 characters long, starting with uppercase letter."));

/**
 * dateinterval method {dateinterval}
 */
jQuery.validator.addMethod("dateinterval", function(value, element, params) {
	return 1;
}, jQuery.format("The dates interval is not valid."));

/**
 * alpha method {alpha}
 */
jQuery.validator.addMethod("alpha", function(value, element) {
	return this.optional(element) || /^[a-zA-Z -]+$/.test(value);
}, jQuery.format("Please use letters only (a-z) in this field."));

/**
 * string method {string}
 */
jQuery.validator.addMethod("string", function(value, element) {
	return this.optional(element) || /^[a-zA-Z -]+$/.test(value);
}, jQuery.format("Please use letters only (a-z) in this field."));

/**
 * exists method {exists} active in server side
 */
jQuery.validator.addMethod("exists", function(value, element, params) {
	return __server_side('exists', value, element, params, this);
}, jQuery.format("Already in database!"));

/**
 * notexists method {notexists} active in server side
 */
jQuery.validator.addMethod("notexists", function(value, element, params) {
	return __server_side('notexists', value, element, params, this);
}, jQuery.format("Not found in database!"));

/**
 * exists_different method {exists_different} active in server side
 */
jQuery.validator.addMethod("exists_different", function(value, element, params) {
	return __server_side('exists_different', value, element, params, this);
}, jQuery.format("Already in database!"));

/**
 * signature method {signature} active in server side
 */
jQuery.validator.addMethod("signature", function(value, element, params) {
	return __server_side('signature', value, element, params, this);
}, jQuery.format("Security code is wrong!"));

/**
 * custom_dal method {custom_dal} active in server side
 */
jQuery.validator.addMethod("custom_dal", function(value, element, params) {
	return __server_side('custom_dal', value, element, params, this);
}, jQuery.format("Condition not approved!"));

/**
 * jQuery Validation Plugin 1.8.1
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2011 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function() {

	function stripHtml(value) {
		// remove html tags and space chars
		return value.replace(/<.[^<>]*?>/g, ' ').replace(/&nbsp;|&#160;/gi, ' ')
		// remove numbers and punctuation
		.replace(/[0-9.(),;:!?%#$'"_+=\/-]*/g, '');
	}


	jQuery.validator.addMethod("maxWords", function(value, element, params) {
		return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length < params;
	}, jQuery.validator.format("Please enter {0} words or less."));

	jQuery.validator.addMethod("minWords", function(value, element, params) {
		return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length >= params;
	}, jQuery.validator.format("Please enter at least {0} words."));

	jQuery.validator.addMethod("rangeWords", function(value, element, params) {
		return this.optional(element) || stripHtml(value).match(/\b\w+\b/g).length >= params[0] && value.match(/bw+b/g).length < params[1];
	}, jQuery.validator.format("Please enter between {0} and {1} words."));

})();

jQuery.validator.addMethod("letterswithbasicpunc", function(value, element) {
	return this.optional(element) || /^[a-z-.,()'\"\s]+$/i.test(value);
}, "Letters or punctuation only please");

jQuery.validator.addMethod("alphanumeric", function(value, element) {
	return this.optional(element) || /^\w+$/i.test(value);
}, "Letters, numbers, spaces or underscores only please");

jQuery.validator.addMethod("lettersonly", function(value, element) {
	return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Letters only please");

jQuery.validator.addMethod("nowhitespace", function(value, element) {
	return this.optional(element) || /^\S+$/i.test(value);
}, "No white space please");

jQuery.validator.addMethod("ziprange", function(value, element) {
	return this.optional(element) || /^90[2-5]\d\{2}-\d{4}$/.test(value);
}, "Your ZIP-code must be in the range 902xx-xxxx to 905-xx-xxxx");

jQuery.validator.addMethod("integer", function(value, element) {
	return this.optional(element) || /^-?\d+$/.test(value);
}, "A positive or negative non-decimal number please");

/**
 * Return true, if the value is a valid vehicle identification number (VIN).
 *
 * Works with all kind of text inputs.
 *
 * @example <input type="text" size="20" name="VehicleID" class="{required:true,vinUS:true}" />
 * @desc Declares a required input element whose value must be a valid vehicle identification number.
 *
 * @name jQuery.validator.methods.vinUS
 * @type Boolean
 * @cat Plugins/Validate/Methods
 */
jQuery.validator.addMethod("vinUS", function(v) {
	if (v.length != 17)
		return false;
	var i, n, d, f, cd, cdv;
	var LL = ["A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
	var VL = [1, 2, 3, 4, 5, 6, 7, 8, 1, 2, 3, 4, 5, 7, 9, 2, 3, 4, 5, 6, 7, 8, 9];
	var FL = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
	var rs = 0;
	for ( i = 0; i < 17; i++) {
		f = FL[i];
		d = v.slice(i, i + 1);
		if (i == 8) {
			cdv = d;
		}
		if (!isNaN(d)) {
			d *= f;
		} else {
			for ( n = 0; n < LL.length; n++) {
				if (d.toUpperCase() === LL[n]) {
					d = VL[n];
					d *= f;
					if (isNaN(cdv) && n == 8) {
						cdv = LL[n];
					}
					break;
				}
			}
		}
		rs += d;
	}
	cd = rs % 11;
	if (cd == 10) {
		cd = "X";
	}
	if (cd == cdv) {
		return true;
	}
	return false;
}, "The specified vehicle identification number (VIN) is invalid.");

/**
 * Return true, if the value is a valid date, also making this formal check dd/mm/yyyy.
 *
 * @example jQuery.validator.methods.date("01/01/1900")
 * @result true
 *
 * @example jQuery.validator.methods.date("01/13/1990")
 * @result false
 *
 * @example jQuery.validator.methods.date("01.01.1900")
 * @result false
 *
 * @example <input name="pippo" class="{dateITA:true}" />
 * @desc Declares an optional input element whose value must be a valid date.
 *
 * @name jQuery.validator.methods.dateITA
 * @type Boolean
 * @cat Plugins/Validate/Methods
 */
jQuery.validator.addMethod("dateITA", function(value, element) {
	var check = false;
	var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
	if (re.test(value)) {
		var adata = value.split('/');
		var gg = parseInt(adata[0], 10);
		var mm = parseInt(adata[1], 10);
		var aaaa = parseInt(adata[2], 10);
		var xdata = new Date(aaaa, mm - 1, gg);
		if ((xdata.getFullYear() == aaaa ) && (xdata.getMonth() == mm - 1 ) && (xdata.getDate() == gg ))
			check = true;
		else
			check = false;
	} else
		check = false;
	return this.optional(element) || check;
}, "Please enter a correct date");

jQuery.validator.addMethod("dateNL", function(value, element) {
	return this.optional(element) || /^\d\d?[\.\/-]\d\d?[\.\/-]\d\d\d?\d?$/.test(value);
}, "Vul hier een geldige datum in.");

jQuery.validator.addMethod("time", function(value, element) {
	return this.optional(element) || /^([01][0-9])|(2[0123]):([0-5])([0-9])$/.test(value);
}, "Please enter a valid time, between 00:00 and 23:59");

/**
 * matches US phone number format
 *
 * where the area code may not start with 1 and the prefix may not start with 1
 * allows '-' or ' ' as a separator and allows parens around area code
 * some people may want to put a '1' in front of their number
 *
 * 1(212)-999-2345
 * or
 * 212 999 2344
 * or
 * 212-999-0983
 *
 * but not
 * 111-123-5434
 * and not
 * 212 123 4567
 */
jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
	phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
}, "Please specify a valid phone number");

jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
	return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/);
}, 'Please specify a valid phone number');

jQuery.validator.addMethod('mobileUK', function(phone_number, element) {
	return this.optional(element) || phone_number.length > 9 && phone_number.match(/^((0|\+44)7(5|6|7|8|9){1}\d{2}\s?\d{6})$/);
}, 'Please specify a valid mobile number');

// TODO check if value starts with <, otherwise don't try stripping anything
jQuery.validator.addMethod("strippedminlength", function(value, element, param) {
	return jQuery(value).text().length >= param;
}, jQuery.validator.format("Please enter at least {0} characters"));

// same as email, but TLD is optional
jQuery.validator.addMethod("email2", function(value, element, param) {
	return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
}, jQuery.validator.messages.email);

// same as url, but TLD is optional
jQuery.validator.addMethod("url2", function(value, element, param) {
	return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
}, jQuery.validator.messages.url);

// NOTICE: Modified version of Castle.Components.Validator.CreditCardValidator
// Redistributed under the the Apache License 2.0 at http://www.apache.org/licenses/LICENSE-2.0
// Valid Types: mastercard, visa, amex, dinersclub, enroute, discover, jcb, unknown, all (overrides all other settings)
jQuery.validator.addMethod("creditcardtypes", function(value, element, param) {

	if (/[^0-9-]+/.test(value))
		return false;

	value = value.replace(/\D/g, "");

	var validTypes = 0x0000;

	if (param.mastercard)
		validTypes |= 0x0001;
	if (param.visa)
		validTypes |= 0x0002;
	if (param.amex)
		validTypes |= 0x0004;
	if (param.dinersclub)
		validTypes |= 0x0008;
	if (param.enroute)
		validTypes |= 0x0010;
	if (param.discover)
		validTypes |= 0x0020;
	if (param.jcb)
		validTypes |= 0x0040;
	if (param.unknown)
		validTypes |= 0x0080;
	if (param.all)
		validTypes = 0x0001 | 0x0002 | 0x0004 | 0x0008 | 0x0010 | 0x0020 | 0x0040 | 0x0080;

	if (validTypes & 0x0001 && /^(51|52|53|54|55)/.test(value)) {//mastercard
		return value.length == 16;
	}
	if (validTypes & 0x0002 && /^(4)/.test(value)) {//visa
		return value.length == 16;
	}
	if (validTypes & 0x0004 && /^(34|37)/.test(value)) {//amex
		return value.length == 15;
	}
	if (validTypes & 0x0008 && /^(300|301|302|303|304|305|36|38)/.test(value)) {//dinersclub
		return value.length == 14;
	}
	if (validTypes & 0x0010 && /^(2014|2149)/.test(value)) {//enroute
		return value.length == 15;
	}
	if (validTypes & 0x0020 && /^(6011)/.test(value)) {//discover
		return value.length == 16;
	}
	if (validTypes & 0x0040 && /^(3)/.test(value)) {//jcb
		return value.length == 16;
	}
	if (validTypes & 0x0040 && /^(2131|1800)/.test(value)) {//jcb
		return value.length == 15;
	}
	if (validTypes & 0x0080) {//unknown
		return true;
	}
	return false;
}, "Please enter a valid credit card number.");

jQuery.validator.addMethod("ipv4", function(value, element, param) {
	return this.optional(element) || /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/i.test(value);
}, "Please enter a valid IP v4 address.");

jQuery.validator.addMethod("ipv6", function(value, element, param) {
	return this.optional(element) || /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
}, "Please enter a valid IP v6 address.");
