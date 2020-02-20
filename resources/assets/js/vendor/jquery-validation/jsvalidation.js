/*!
 * Laravel Javascript Validation
 *
 * https://github.com/proengsoft/laravel-jsvalidation
 *
 * Copyright (c) 2017 Proengsoft
 * Released under the MIT license
 */

var laravelValidation;
laravelValidation = {

	implicitRules: ['Required','Confirmed'],

	/**
	 * Initialize laravel validations.
	 */
	init: function () {

		// Disable class rules and attribute rules
		$.validator.classRuleSettings = {};
		$.validator.attributeRules = function () {
			this.rules = {}
		};

		$.validator.dataRules = this.arrayRules;
		$.validator.prototype.arrayRulesCache = {};
		// Register validations methods
		this.setupValidations();
	},

	arrayRules: function(element) {

		var rules = {},
			validator = $.data( element.form, "validator"),
			cache = validator.arrayRulesCache;

		// Is not an Array
		if (element.name.indexOf('[') === -1 ) {
			return rules;
		}

		if (! (element.name in cache) ) {
			cache[element.name]={};
		}

		$.each(validator.settings.rules, function(name, tmpRules){
			if (name in cache[element.name]) {
				$.extend(rules, cache[element.name][name]);
			} else {
				cache[element.name][name]={};
				var nameRegExp = laravelValidation.helpers.regexFromWildcard(name);
				if (element.name.match(nameRegExp)) {
					var newRules = $.validator.normalizeRule( tmpRules ) || {};
					cache[element.name][name]=newRules;
					$.extend(rules, newRules);
				}
			}
		});

		return rules;
	},

	setupValidations: function () {

		/**
		 * Create JQueryValidation check to validate Laravel rules.
		 */

		$.validator.addMethod("laravelValidation", function (value, element, params) {
			var validator = this;
			var validated = true;
			var previous = this.previousValue( element );

			// put Implicit rules in front
			var rules=[];
			$.each(params, function (i, param) {
				if (param[3] || laravelValidation.implicitRules.indexOf(param[0])!== -1) {
					rules.unshift(param);
				} else {
					rules.push(param);
				}
			});

			$.each(rules, function (i, param) {
				var implicit = param[3] || laravelValidation.implicitRules.indexOf(param[0])!== -1;
				var rule = param[0];
				var message = param[2];

				if ( !implicit && validator.optional( element ) ) {
					validated="dependency-mismatch";
					return false;
				}

				if (laravelValidation.methods[rule]!==undefined) {
					validated = laravelValidation.methods[rule].call(validator, value, element, param[1], function(valid) {
						validator.settings.messages[ element.name ].laravelValidationRemote = previous.originalMessage;
						if ( valid ) {
							var submitted = validator.formSubmitted;
							validator.prepareElement( element );
							validator.formSubmitted = submitted;
							validator.successList.push( element );
							delete validator.invalid[ element.name ];
							validator.showErrors();
						} else {
							var errors = {};
							errors[ element.name ] = previous.message = $.isFunction( message ) ? message( value ) : message;
							validator.invalid[ element.name ] = true;
							validator.showErrors( errors );
						}
						validator.showErrors(validator.errorMap);
						previous.valid = valid;
					});
				} else {
					validated=false;
				}

				if (validated !== true) {
					if (!validator.settings.messages[ element.name ] ) {
						validator.settings.messages[ element.name ] = {};
					}
					validator.settings.messages[element.name].laravelValidation= message;
					return false;
				}

			});
			return validated;

		}, '');


		/**
		 * Create JQueryValidation check to validate Remote Laravel rules.
		 */
		$.validator.addMethod("laravelValidationRemote", function (value, element, params) {

			var implicit = false,
				check = params[0][1],
				attribute = element.name,
				token = check[1],
				validateAll = check[2];

			$.each(params, function (i, parameters) {
				implicit = implicit || parameters[3];
			});


			if ( !implicit && this.optional( element ) ) {
				return "dependency-mismatch";
			}

			var previous = this.previousValue( element ),
				validator, data;

			if (!this.settings.messages[ element.name ] ) {
				this.settings.messages[ element.name ] = {};
			}
			previous.originalMessage = this.settings.messages[ element.name ].laravelValidationRemote;
			this.settings.messages[ element.name ].laravelValidationRemote = previous.message;

			var param = typeof param === "string" && { url: param } || param;

			if (laravelValidation.helpers.arrayEquals(previous.old, value) || previous.old === value) {
				return previous.valid;
			}

			previous.old = value;
			validator = this;
			this.startRequest( element );

			data = $(validator.currentForm).serializeArray();

			data.push({
				'name': '_jsvalidation',
				'value': attribute
			});

			data.push({
				'name': '_jsvalidation_validate_all',
				'value': validateAll
			});

			var formMethod = $(validator.currentForm).attr('method');
			if($(validator.currentForm).find('input[name="_method"]').length) {
				formMethod = $(validator.currentForm).find('input[name="_method"]').val();
			}

			$.ajax( $.extend( true, {
					mode: "abort",
					port: "validate" + element.name,
					dataType: "json",
					data: data,
					context: validator.currentForm,
					url: $(validator.currentForm).attr('action'),
					type: formMethod,

					beforeSend: function (xhr) {
						if ($(validator.currentForm).attr('method').toLowerCase() !== 'get' && token) {
							return xhr.setRequestHeader('X-XSRF-TOKEN', token);
						}
					}
				}, param )
			).always(function( response, textStatus ) {
					var errors, message, submitted, valid;

					if (textStatus === 'error') {
						valid = false;
						response = laravelValidation.helpers.parseErrorResponse(response);
					} else if (textStatus === 'success') {
						valid = response === true || response === "true";
					} else {
						return;
					}

					validator.settings.messages[ element.name ].laravelValidationRemote = previous.originalMessage;

					if ( valid ) {
						submitted = validator.formSubmitted;
						validator.prepareElement( element );
						validator.formSubmitted = submitted;
						validator.successList.push( element );
						delete validator.invalid[ element.name ];
						validator.showErrors();
					} else {
						errors = {};
						message = response || validator.defaultMessage( element, "remote" );
						errors[ element.name ] = previous.message = $.isFunction( message ) ? message( value ) : message[0];
						validator.invalid[ element.name ] = true;
						validator.showErrors( errors );
					}
					validator.showErrors(validator.errorMap);
					previous.valid = valid;
					validator.stopRequest( element, valid );
				}
			);
			return "pending";
		}, '');
	}
};

$(function() {
	laravelValidation.init();
});

/*!
 * Laravel Javascript Validation
 *
 * https://github.com/proengsoft/laravel-jsvalidation
 *
 * Helper functions used by validators
 *
 * Copyright (c) 2017 Proengsoft
 * Released under the MIT license
 */

$.extend(true, laravelValidation, {

	helpers: {

		/**
		 * Numeric rules
		 */
		numericRules: ['Integer', 'Numeric'],

		/**
		 * Gets the file information from file input.
		 *
		 * @param fieldObj
		 * @param index
		 * @returns {{file: *, extension: string, size: number}}
		 */
		fileinfo: function (fieldObj, index) {
			var FileName = fieldObj.value;
			index = typeof index !== 'undefined' ? index : 0;
			if ( fieldObj.files !== null ) {
				if (typeof fieldObj.files[index] !== 'undefined') {
					return {
						file: FileName,
						extension: FileName.substr(FileName.lastIndexOf('.') + 1),
						size: fieldObj.files[index].size / 1024,
						type: fieldObj.files[index].type
					};
				}
			}
			return false;
		},


		/**
		 * Gets the selectors for th specified field names.
		 *
		 * @param names
		 * @returns {string}
		 */
		selector: function (names) {
			var selector = [];
			if (!$.isArray(names))  {
				names = [names];
			}
			for (var i = 0; i < names.length; i++) {
				selector.push("[name='" + names[i] + "']");
			}
			return selector.join();
		},


		/**
		 * Check if element has numeric rules.
		 *
		 * @param element
		 * @returns {boolean}
		 */
		hasNumericRules: function (element) {
			return this.hasRules(element, this.numericRules);
		},

		/**
		 * Check if element has passed rules.
		 *
		 * @param element
		 * @param rules
		 * @returns {boolean}
		 */
		hasRules: function (element, rules) {

			var found = false;
			if (typeof rules === 'string') {
				rules = [rules];
			}

			var validator = $.data(element.form, "validator");
			var listRules = [];
			var cache = validator.arrayRulesCache;
			if (element.name in cache) {
				$.each(cache[element.name], function (index, arrayRule) {
					listRules.push(arrayRule);
				});
			}
			if (element.name in validator.settings.rules) {
				listRules.push(validator.settings.rules[element.name]);
			}
			$.each(listRules, function(index,objRules){
				if ('laravelValidation' in objRules) {
					var _rules=objRules.laravelValidation;
					for (var i = 0; i < _rules.length; i++) {
						if ($.inArray(_rules[i][0],rules) !== -1) {
							found = true;
							return false;
						}
					}
				}
			});

			return found;
		},

		/**
		 * Return the string length using PHP function.
		 * http://php.net/manual/en/function.strlen.php
		 * http://phpjs.org/functions/strlen/
		 *
		 * @param string
		 */
		strlen: function (string) {
			return strlen(string);
		},

		/**
		 * Get the size of the object depending of his type.
		 *
		 * @param obj
		 * @param element
		 * @param value
		 * @returns int
		 */
		getSize: function getSize(obj, element, value) {

			if (this.hasNumericRules(element) && this.is_numeric(value)) {
				return parseFloat(value);
			} else if ($.isArray(value)) {
				return parseFloat(value.length);
			} else if (element.type === 'file') {
				return parseFloat(Math.floor(this.fileinfo(element).size));
			}

			return parseFloat(this.strlen(value));
		},


		/**
		 * Return specified rule from element.
		 *
		 * @param rule
		 * @param element
		 * @returns object
		 */
		getLaravelValidation: function(rule, element) {

			var found = undefined;
			$.each($.validator.staticRules(element), function(key, rules) {
				if (key==="laravelValidation") {
					$.each(rules, function (i, value) {
						if (value[0]===rule) {
							found=value;
						}
					});
				}
			});

			return found;
		},

		/**
		 * Return he timestamp of value passed using format or default format in element.
		 *
		 * @param value
		 * @param format
		 * @returns {boolean|int}
		 */
		parseTime: function (value, format) {

			var timeValue = false;
			var fmt = new DateFormatter();

			if ($.type(format) === 'object') {
				var dateRule=this.getLaravelValidation('DateFormat', format);
				if (dateRule !== undefined) {
					format = dateRule[1][0];
				} else {
					format = null;
				}
			}

			if (format == null) {
				timeValue = this.strtotime(value);
			} else {
				timeValue = fmt.parseDate(value, format);
				if (timeValue) {
					timeValue = Math.round((timeValue.getTime() / 1000));
				}
			}

			return timeValue;
		},

		/**
		 * This method allows you to intelligently guess the date by closely matching the specific format.
		 *
		 * @param value
		 * @param format
		 * @returns {Date}
		 */
		guessDate: function (value, format) {
			var fmt = new DateFormatter();
			return fmt.guessDate(value, format)
		},

		/**
		 * Returns Unix timestamp based on PHP function strototime.
		 * http://php.net/manual/es/function.strtotime.php
		 * http://phpjs.org/functions/strtotime/
		 *
		 * @param text
		 * @param now
		 * @returns {*}
		 */
		strtotime: function (text, now) {
			return strtotime(text, now)
		},

		/**
		 * Returns if value is numeric.
		 * http://php.net/manual/es/var.is_numeric.php
		 * http://phpjs.org/functions/is_numeric/
		 *
		 * @param mixed_var
		 * @returns {*}
		 */
		is_numeric: function (mixed_var) {
			return is_numeric(mixed_var)
		},

		/**
		 * Returns Array diff based on PHP function array_diff.
		 * http://php.net/manual/es/function.array_diff.php
		 * http://phpjs.org/functions/array_diff/
		 *
		 * @param arr1
		 * @param arr2
		 * @returns {*}
		 */
		arrayDiff: function (arr1, arr2) {
			return array_diff(arr1, arr2);
		},

		/**
		 * Check whether two arrays are equal to one another.
		 *
		 * @param arr1
		 * @param arr2
		 * @returns {*}
		 */
		arrayEquals: function (arr1, arr2) {
			if (! $.isArray(arr1) || ! $.isArray(arr2)) {
				return false;
			}

			if (arr1.length !== arr2.length) {
				return false;
			}

			return $.isEmptyObject(this.arrayDiff(arr1, arr2));
		},

		/**
		 * Makes element dependant from other.
		 *
		 * @param validator
		 * @param element
		 * @param name
		 * @returns {*}
		 */
		dependentElement: function(validator, element, name) {

			var el=validator.findByName(name);

			if ( el[0]!==undefined  && validator.settings.onfocusout ) {
				var event = 'blur';
				if (el[0].tagName === 'SELECT' ||
					el[0].tagName === 'OPTION' ||
					el[0].type === 'checkbox' ||
					el[0].type === 'radio'
				) {
					event = 'click';
				}

				var ruleName = '.validate-laravelValidation';
				el.off( ruleName )
					.off(event + ruleName + '-' + element.name)
					.on( event + ruleName + '-' + element.name, function() {
						$( element ).valid();
					});
			}

			return el[0];
		},

		/**
		 * Parses error Ajax response and gets the message.
		 *
		 * @param response
		 * @returns {string[]}
		 */
		parseErrorResponse: function (response) {
			var newResponse = ['Whoops, looks like something went wrong.'];
			if ('responseText' in response) {
				var errorMsg = response.responseText.match(/<h1\s*>(.*)<\/h1\s*>/i);
				if ($.isArray(errorMsg)) {
					newResponse = [errorMsg[1]];
				}
			}
			return newResponse;
		},

		/**
		 * Escape string to use as Regular Expression.
		 *
		 * @param str
		 * @returns string
		 */
		escapeRegExp: function (str) {
			return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
		},

		/**
		 * Generate RegExp from wildcard attributes.
		 *
		 * @param name
		 * @returns {RegExp}
		 */
		regexFromWildcard: function(name) {
			var nameParts = name.split("[*]");
			if (nameParts.length === 1) {
				nameParts.push('');
			}
			var regexpParts = nameParts.map(function(currentValue, index) {
				if (index % 2 === 0) {
					currentValue = currentValue + '[';
				} else {
					currentValue = ']' +currentValue;
				}

				return laravelValidation.helpers.escapeRegExp(currentValue);
			});

			return new RegExp('^'+regexpParts.join('.*')+'$');
		}
	}
});

/*!
 * Laravel Javascript Validation
 *
 * https://github.com/proengsoft/laravel-jsvalidation
 *
 * Timezone Helper functions used by validators
 *
 * Copyright (c) 2017 Proengsoft
 * Released under the MIT license
 */

$.extend(true, laravelValidation, {

	helpers: {

		/**
		 * Check if the specified timezone is valid.
		 *
		 * @param value
		 * @returns {boolean}
		 */
		isTimezone: function (value) {

			var timezones={
				"africa": [
					"abidjan",
					"accra",
					"addis_ababa",
					"algiers",
					"asmara",
					"bamako",
					"bangui",
					"banjul",
					"bissau",
					"blantyre",
					"brazzaville",
					"bujumbura",
					"cairo",
					"casablanca",
					"ceuta",
					"conakry",
					"dakar",
					"dar_es_salaam",
					"djibouti",
					"douala",
					"el_aaiun",
					"freetown",
					"gaborone",
					"harare",
					"johannesburg",
					"juba",
					"kampala",
					"khartoum",
					"kigali",
					"kinshasa",
					"lagos",
					"libreville",
					"lome",
					"luanda",
					"lubumbashi",
					"lusaka",
					"malabo",
					"maputo",
					"maseru",
					"mbabane",
					"mogadishu",
					"monrovia",
					"nairobi",
					"ndjamena",
					"niamey",
					"nouakchott",
					"ouagadougou",
					"porto-novo",
					"sao_tome",
					"tripoli",
					"tunis",
					"windhoek"
				],
				"america": [
					"adak",
					"anchorage",
					"anguilla",
					"antigua",
					"araguaina",
					"argentina\/buenos_aires",
					"argentina\/catamarca",
					"argentina\/cordoba",
					"argentina\/jujuy",
					"argentina\/la_rioja",
					"argentina\/mendoza",
					"argentina\/rio_gallegos",
					"argentina\/salta",
					"argentina\/san_juan",
					"argentina\/san_luis",
					"argentina\/tucuman",
					"argentina\/ushuaia",
					"aruba",
					"asuncion",
					"atikokan",
					"bahia",
					"bahia_banderas",
					"barbados",
					"belem",
					"belize",
					"blanc-sablon",
					"boa_vista",
					"bogota",
					"boise",
					"cambridge_bay",
					"campo_grande",
					"cancun",
					"caracas",
					"cayenne",
					"cayman",
					"chicago",
					"chihuahua",
					"costa_rica",
					"creston",
					"cuiaba",
					"curacao",
					"danmarkshavn",
					"dawson",
					"dawson_creek",
					"denver",
					"detroit",
					"dominica",
					"edmonton",
					"eirunepe",
					"el_salvador",
					"fortaleza",
					"glace_bay",
					"godthab",
					"goose_bay",
					"grand_turk",
					"grenada",
					"guadeloupe",
					"guatemala",
					"guayaquil",
					"guyana",
					"halifax",
					"havana",
					"hermosillo",
					"indiana\/indianapolis",
					"indiana\/knox",
					"indiana\/marengo",
					"indiana\/petersburg",
					"indiana\/tell_city",
					"indiana\/vevay",
					"indiana\/vincennes",
					"indiana\/winamac",
					"inuvik",
					"iqaluit",
					"jamaica",
					"juneau",
					"kentucky\/louisville",
					"kentucky\/monticello",
					"kralendijk",
					"la_paz",
					"lima",
					"los_angeles",
					"lower_princes",
					"maceio",
					"managua",
					"manaus",
					"marigot",
					"martinique",
					"matamoros",
					"mazatlan",
					"menominee",
					"merida",
					"metlakatla",
					"mexico_city",
					"miquelon",
					"moncton",
					"monterrey",
					"montevideo",
					"montreal",
					"montserrat",
					"nassau",
					"new_york",
					"nipigon",
					"nome",
					"noronha",
					"north_dakota\/beulah",
					"north_dakota\/center",
					"north_dakota\/new_salem",
					"ojinaga",
					"panama",
					"pangnirtung",
					"paramaribo",
					"phoenix",
					"port-au-prince",
					"port_of_spain",
					"porto_velho",
					"puerto_rico",
					"rainy_river",
					"rankin_inlet",
					"recife",
					"regina",
					"resolute",
					"rio_branco",
					"santa_isabel",
					"santarem",
					"santiago",
					"santo_domingo",
					"sao_paulo",
					"scoresbysund",
					"shiprock",
					"sitka",
					"st_barthelemy",
					"st_johns",
					"st_kitts",
					"st_lucia",
					"st_thomas",
					"st_vincent",
					"swift_current",
					"tegucigalpa",
					"thule",
					"thunder_bay",
					"tijuana",
					"toronto",
					"tortola",
					"vancouver",
					"whitehorse",
					"winnipeg",
					"yakutat",
					"yellowknife"
				],
				"antarctica": [
					"casey",
					"davis",
					"dumontdurville",
					"macquarie",
					"mawson",
					"mcmurdo",
					"palmer",
					"rothera",
					"south_pole",
					"syowa",
					"vostok"
				],
				"arctic": [
					"longyearbyen"
				],
				"asia": [
					"aden",
					"almaty",
					"amman",
					"anadyr",
					"aqtau",
					"aqtobe",
					"ashgabat",
					"baghdad",
					"bahrain",
					"baku",
					"bangkok",
					"beirut",
					"bishkek",
					"brunei",
					"choibalsan",
					"chongqing",
					"colombo",
					"damascus",
					"dhaka",
					"dili",
					"dubai",
					"dushanbe",
					"gaza",
					"harbin",
					"hebron",
					"ho_chi_minh",
					"hong_kong",
					"hovd",
					"irkutsk",
					"jakarta",
					"jayapura",
					"jerusalem",
					"kabul",
					"kamchatka",
					"karachi",
					"kashgar",
					"kathmandu",
					"khandyga",
					"kolkata",
					"krasnoyarsk",
					"kuala_lumpur",
					"kuching",
					"kuwait",
					"macau",
					"magadan",
					"makassar",
					"manila",
					"muscat",
					"nicosia",
					"novokuznetsk",
					"novosibirsk",
					"omsk",
					"oral",
					"phnom_penh",
					"pontianak",
					"pyongyang",
					"qatar",
					"qyzylorda",
					"rangoon",
					"riyadh",
					"sakhalin",
					"samarkand",
					"seoul",
					"shanghai",
					"singapore",
					"taipei",
					"tashkent",
					"tbilisi",
					"tehran",
					"thimphu",
					"tokyo",
					"ulaanbaatar",
					"urumqi",
					"ust-nera",
					"vientiane",
					"vladivostok",
					"yakutsk",
					"yekaterinburg",
					"yerevan"
				],
				"atlantic": [
					"azores",
					"bermuda",
					"canary",
					"cape_verde",
					"faroe",
					"madeira",
					"reykjavik",
					"south_georgia",
					"st_helena",
					"stanley"
				],
				"australia": [
					"adelaide",
					"brisbane",
					"broken_hill",
					"currie",
					"darwin",
					"eucla",
					"hobart",
					"lindeman",
					"lord_howe",
					"melbourne",
					"perth",
					"sydney"
				],
				"europe": [
					"amsterdam",
					"andorra",
					"athens",
					"belgrade",
					"berlin",
					"bratislava",
					"brussels",
					"bucharest",
					"budapest",
					"busingen",
					"chisinau",
					"copenhagen",
					"dublin",
					"gibraltar",
					"guernsey",
					"helsinki",
					"isle_of_man",
					"istanbul",
					"jersey",
					"kaliningrad",
					"kiev",
					"lisbon",
					"ljubljana",
					"london",
					"luxembourg",
					"madrid",
					"malta",
					"mariehamn",
					"minsk",
					"monaco",
					"moscow",
					"oslo",
					"paris",
					"podgorica",
					"prague",
					"riga",
					"rome",
					"samara",
					"san_marino",
					"sarajevo",
					"simferopol",
					"skopje",
					"sofia",
					"stockholm",
					"tallinn",
					"tirane",
					"uzhgorod",
					"vaduz",
					"vatican",
					"vienna",
					"vilnius",
					"volgograd",
					"warsaw",
					"zagreb",
					"zaporozhye",
					"zurich"
				],
				"indian": [
					"antananarivo",
					"chagos",
					"christmas",
					"cocos",
					"comoro",
					"kerguelen",
					"mahe",
					"maldives",
					"mauritius",
					"mayotte",
					"reunion"
				],
				"pacific": [
					"apia",
					"auckland",
					"chatham",
					"chuuk",
					"easter",
					"efate",
					"enderbury",
					"fakaofo",
					"fiji",
					"funafuti",
					"galapagos",
					"gambier",
					"guadalcanal",
					"guam",
					"honolulu",
					"johnston",
					"kiritimati",
					"kosrae",
					"kwajalein",
					"majuro",
					"marquesas",
					"midway",
					"nauru",
					"niue",
					"norfolk",
					"noumea",
					"pago_pago",
					"palau",
					"pitcairn",
					"pohnpei",
					"port_moresby",
					"rarotonga",
					"saipan",
					"tahiti",
					"tarawa",
					"tongatapu",
					"wake",
					"wallis"
				],
				"utc": [
					""
				]
			};

			var tzparts= value.split('/',2);
			var continent=tzparts[0].toLowerCase();
			var city='';
			if (tzparts[1]) {
				city=tzparts[1].toLowerCase();
			}

			return (continent in timezones && ( timezones[continent].length===0 || timezones[continent].indexOf(city)!==-1))
		}
	}
});

/*!
 * Laravel Javascript Validation
 *
 * https://github.com/proengsoft/laravel-jsvalidation
 *
 * Methods that implement Laravel Validations
 *
 * Copyright (c) 2017 Proengsoft
 * Released under the MIT license
 */

$.extend(true, laravelValidation, {

	methods:{

		helpers: laravelValidation.helpers,

		jsRemoteTimer:0,

		/**
		 * "Validate" optional attributes.
		 * Always returns true, just lets us put sometimes in rules.
		 *
		 * @return {boolean}
		 */
		Sometimes: function() {
			return true;
		},

		/**
		 * Bail This is the default behaivour os JSValidation.
		 * Always returns true, just lets us put sometimes in rules.
		 *
		 * @return {boolean}
		 */
		Bail: function() {
			return true;
		},

		/**
		 * "Indicate" validation should pass if value is null.
		 * Always returns true, just lets us put "nullable" in rules.
		 *
		 * @return {boolean}
		 */
		Nullable: function() {
			return true;
		},

		/**
		 * Validate the given attribute is filled if it is present.
		 */
		Filled: function(value, element) {
			return $.validator.methods.required.call(this, value, element, true);
		},


		/**
		 * Validate that a required attribute exists.
		 */
		Required: function(value, element) {
			return  $.validator.methods.required.call(this, value, element);
		},

		/**
		 * Validate that an attribute exists when any other attribute exists.
		 *
		 * @return {boolean}
		 */
		RequiredWith: function(value, element, params) {
			var validator=this,
				required=false;
			var currentObject=this;

			$.each(params,function(i,param) {
				var target=laravelValidation.helpers.dependentElement(
					currentObject, element, param
				);
				required=required || (
					target!==undefined &&
					$.validator.methods.required.call(
						validator,
						currentObject.elementValue(target),
						target,true
					));
			});

			if (required) {
				return  $.validator.methods.required.call(this, value, element, true);
			}
			return true;
		},

		/**
		 * Validate that an attribute exists when all other attribute exists.
		 *
		 * @return {boolean}
		 */
		RequiredWithAll: function(value, element, params) {
			var validator=this,
				required=true;
			var currentObject=this;

			$.each(params,function(i,param) {
				var target=laravelValidation.helpers.dependentElement(
					currentObject, element, param
				);
				required = required && (
					target!==undefined &&
					$.validator.methods.required.call(
						validator,
						currentObject.elementValue(target),
						target,true
					));
			});

			if (required) {
				return  $.validator.methods.required.call(this, value, element, true);
			}
			return true;
		},

		/**
		 * Validate that an attribute exists when any other attribute does not exists.
		 *
		 * @return {boolean}
		 */
		RequiredWithout: function(value, element, params) {
			var validator=this,
				required=false;
			var currentObject=this;

			$.each(params,function(i,param) {
				var target=laravelValidation.helpers.dependentElement(
					currentObject, element, param
				);
				required = required ||
					target===undefined||
					!$.validator.methods.required.call(
						validator,
						currentObject.elementValue(target),
						target,true
					);
			});

			if (required) {
				return  $.validator.methods.required.call(this, value, element, true);
			}
			return true;
		},

		/**
		 * Validate that an attribute exists when all other attribute does not exists.
		 *
		 * @return {boolean}
		 */
		RequiredWithoutAll: function(value, element, params) {
			var validator=this,
				required=true,
				currentObject=this;

			$.each(params,function(i, param) {
				var target=laravelValidation.helpers.dependentElement(
					currentObject, element, param
				);
				required = required && (
					target===undefined ||
					!$.validator.methods.required.call(
						validator,
						currentObject.elementValue(target),
						target,true
					));
			});

			if (required) {
				return  $.validator.methods.required.call(this, value, element, true);
			}
			return true;
		},

		/**
		 * Validate that an attribute exists when another attribute has a given value.
		 *
		 * @return {boolean}
		 */
		RequiredIf: function(value, element, params) {

			var target=laravelValidation.helpers.dependentElement(
				this, element, params[0]
			);

			if (target!==undefined) {
				var val=String(this.elementValue(target));
				if (typeof val !== 'undefined') {
					var data = params.slice(1);
					if ($.inArray(val, data) !== -1) {
						return $.validator.methods.required.call(
							this, value, element, true
						);
					}
				}
			}

			return true;
		},

		/**
		 * Validate that an attribute exists when another
		 * attribute does not have a given value.
		 *
		 * @return {boolean}
		 */
		RequiredUnless: function(value, element, params) {

			var target=laravelValidation.helpers.dependentElement(
				this, element, params[0]
			);

			if (target!==undefined) {
				var val=String(this.elementValue(target));
				if (typeof val !== 'undefined') {
					var data = params.slice(1);
					if ($.inArray(val, data) !== -1) {
						return true;
					}
				}
			}

			return $.validator.methods.required.call(
				this, value, element, true
			);

		},

		/**
		 * Validate that an attribute has a matching confirmation.
		 *
		 * @return {boolean}
		 */
		Confirmed: function(value, element, params) {
			return laravelValidation.methods.Same.call(this,value, element, params);
		},

		/**
		 * Validate that two attributes match.
		 *
		 * @return {boolean}
		 */
		Same: function(value, element, params) {

			var target=laravelValidation.helpers.dependentElement(
				this, element, params[0]
			);

			if (target!==undefined) {
				return String(value) === String(this.elementValue(target));
			}
			return false;
		},

		/**
		 * Validate that the values of an attribute is in another attribute.
		 *
		 * @param value
		 * @param element
		 * @param params
		 * @returns {boolean}
		 * @constructor
		 */
		InArray: function (value, element, params) {
			if (typeof params[0] === 'undefined') {
				return false;
			}
			var elements = this.elements();
			var found = false;
			var nameRegExp = laravelValidation.helpers.regexFromWildcard(params[0]);

			for ( var i = 0; i < elements.length ; i++ ) {
				var targetName = elements[i].name;
				if (targetName.match(nameRegExp)) {
					var equals = laravelValidation.methods.Same.call(this,value, element, [targetName]);
					found = found || equals;
				}
			}

			return found;
		},

		/**
		 * Validate an attribute is unique among other values.
		 *
		 * @param value
		 * @param element
		 * @param params
		 * @returns {boolean}
		 */
		Distinct: function (value, element, params) {
			if (typeof params[0] === 'undefined') {
				return false;
			}

			var elements = this.elements();
			var found = false;
			var nameRegExp = laravelValidation.helpers.regexFromWildcard(params[0]);

			for ( var i = 0; i < elements.length ; i++ ) {
				var targetName = elements[i].name;
				if (targetName !== element.name && targetName.match(nameRegExp)) {
					var equals = laravelValidation.methods.Same.call(this,value, element, [targetName]);
					found = found || equals;
				}
			}

			return !found;
		},


		/**
		 * Validate that an attribute is different from another attribute.
		 *
		 * @return {boolean}
		 */
		Different: function(value, element, params) {
			return ! laravelValidation.methods.Same.call(this,value, element, params);
		},

		/**
		 * Validate that an attribute was "accepted".
		 * This validation rule implies the attribute is "required".
		 *
		 * @return {boolean}
		 */
		Accepted: function(value) {
			var regex = new RegExp("^(?:(yes|on|1|true))$",'i');
			return regex.test(value);
		},

		/**
		 * Validate that an attribute is an array.
		 *
		 * @param value
		 * @param element
		 */
		Array: function(value, element) {
			if (element.name.indexOf('[') !== -1 && element.name.indexOf(']') !== -1) {
				return true;
			}

			return $.isArray(value);
		},

		/**
		 * Validate that an attribute is a boolean.
		 *
		 * @return {boolean}
		 */
		Boolean: function(value) {
			var regex= new RegExp("^(?:(true|false|1|0))$",'i');
			return  regex.test(value);
		},

		/**
		 * Validate that an attribute is an integer.
		 *
		 * @return {boolean}
		 */
		Integer: function(value) {
			var regex= new RegExp("^(?:-?\\d+)$",'i');
			return  regex.test(value);
		},

		/**
		 * Validate that an attribute is numeric.
		 */
		Numeric: function(value, element) {
			return $.validator.methods.number.call(this, value, element, true);
		},

		/**
		 * Validate that an attribute is a string.
		 *
		 * @return {boolean}
		 */
		String: function(value) {
			return typeof value === 'string';
		},

		/**
		 * The field under validation must be numeric and must have an exact length of value.
		 */
		Digits: function(value, element, params) {
			return (
				$.validator.methods.number.call(this, value, element, true) &&
				value.length === parseInt(params, 10)
			);
		},

		/**
		 * The field under validation must have a length between the given min and max.
		 */
		DigitsBetween: function(value, element, params) {
			return ($.validator.methods.number.call(this, value, element, true)
				&& value.length>=parseFloat(params[0]) && value.length<=parseFloat(params[1]));
		},

		/**
		 * Validate the size of an attribute.
		 *
		 * @return {boolean}
		 */
		Size: function(value, element, params) {
			return laravelValidation.helpers.getSize(this, element,value) === parseFloat(params[0]);
		},

		/**
		 * Validate the size of an attribute is between a set of values.
		 *
		 * @return {boolean}
		 */
		Between: function(value, element, params) {
			return ( laravelValidation.helpers.getSize(this, element,value) >= parseFloat(params[0]) &&
				laravelValidation.helpers.getSize(this,element,value) <= parseFloat(params[1]));
		},

		/**
		 * Validate the size of an attribute is greater than a minimum value.
		 *
		 * @return {boolean}
		 */
		Min: function(value, element, params) {
			return laravelValidation.helpers.getSize(this, element,value) >= parseFloat(params[0]);
		},

		/**
		 * Validate the size of an attribute is less than a maximum value.
		 *
		 * @return {boolean}
		 */
		Max: function(value, element, params) {
			return laravelValidation.helpers.getSize(this, element,value) <= parseFloat(params[0]);
		},

		/**
		 * Validate an attribute is contained within a list of values.
		 *
		 * @return {boolean}
		 */
		In: function(value, element, params) {
			if ($.isArray(value) && laravelValidation.helpers.hasRules(element, "Array")) {
				var diff = laravelValidation.helpers.arrayDiff(value, params);
				return Object.keys(diff).length === 0;
			}
			return params.indexOf(value.toString()) !== -1;
		},

		/**
		 * Validate an attribute is not contained within a list of values.
		 *
		 * @return {boolean}
		 */
		NotIn: function(value, element, params) {
			return params.indexOf(value.toString()) === -1;
		},

		/**
		 * Validate that an attribute is a valid IP.
		 *
		 * @return {boolean}
		 */
		Ip: function(value) {
			return /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(value) ||
				/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
		},

		/**
		 * Validate that an attribute is a valid e-mail address.
		 */
		Email: function(value, element) {
			return $.validator.methods.email.call(this, value, element, true);
		},

		/**
		 * Validate that an attribute is a valid URL.
		 */
		Url: function(value, element) {
			return $.validator.methods.url.call(this, value, element, true);
		},

		/**
		 * The field under validation must be a successfully uploaded file.
		 *
		 * @return {boolean}
		 */
		File: function(value, element) {
			if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
				return true;
			}
			if ('files' in element ) {
				return (element.files.length > 0);
			}
			return false;
		},

		/**
		 * Validate the MIME type of a file upload attribute is in a set of MIME types.
		 *
		 * @return {boolean}
		 */
		Mimes: function(value, element, params) {
			if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
				return true;
			}
			var lowerParams = $.map(params, function(item) {
				return item.toLowerCase();
			});

			var fileinfo = laravelValidation.helpers.fileinfo(element);
			return (fileinfo !== false && lowerParams.indexOf(fileinfo.extension.toLowerCase())!==-1);
		},

		/**
		 * The file under validation must match one of the given MIME types.
		 *
		 * @return {boolean}
		 */
		Mimetypes: function(value, element, params) {
			if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
				return true;
			}
			var lowerParams = $.map(params, function(item) {
				return item.toLowerCase();
			});

			var fileinfo = laravelValidation.helpers.fileinfo(element);

			if (fileinfo === false) {
				return false;
			}
			return (lowerParams.indexOf(fileinfo.type.toLowerCase())!==-1);
		},

		/**
		 * Validate the MIME type of a file upload attribute is in a set of MIME types.
		 */
		Image: function(value, element) {
			return laravelValidation.methods.Mimes.call(this, value, element, [
				'jpg', 'png', 'gif', 'bmp', 'svg', 'jpeg'
			]);
		},

		/**
		 * Validate dimensions of Image.
		 *
		 * @return {boolean|string}
		 */
		Dimensions: function(value, element, params, callback) {
			if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
				return true;
			}
			if (element.files === null || typeof element.files[0] === 'undefined') {
				return false;
			}

			var fr = new FileReader;
			fr.onload = function () {
				var img = new Image();
				img.onload = function () {
					var height = parseFloat(img.naturalHeight);
					var width = parseFloat(img.naturalWidth);
					var ratio = width / height;
					var notValid = ((params['width']) && parseFloat(params['width'] !== width)) ||
						((params['min_width']) && parseFloat(params['min_width']) > width) ||
						((params['max_width']) && parseFloat(params['max_width']) < width) ||
						((params['height']) && parseFloat(params['height']) !== height) ||
						((params['min_height']) && parseFloat(params['min_height']) > height) ||
						((params['max_height']) && parseFloat(params['max_height']) < height) ||
						((params['ratio']) && ratio !== parseFloat(eval(params['ratio']))
						);
					callback(! notValid);
				};
				img.onerror = function() {
					callback(false);
				};
				img.src = fr.result;
			};
			fr.readAsDataURL(element.files[0]);

			return 'pending';
		},

		/**
		 * Validate that an attribute contains only alphabetic characters.
		 *
		 * @return {boolean}
		 */
		Alpha: function(value) {
			if (typeof  value !== 'string') {
				return false;
			}

			var regex = new RegExp("^(?:^[a-z\u00E0-\u00FC]+$)$",'i');
			return  regex.test(value);

		},

		/**
		 * Validate that an attribute contains only alpha-numeric characters.
		 *
		 * @return {boolean}
		 */
		AlphaNum: function(value) {
			if (typeof  value !== 'string') {
				return false;
			}
			var regex = new RegExp("^(?:^[a-z0-9\u00E0-\u00FC]+$)$",'i');
			return regex.test(value);
		},

		/**
		 * Validate that an attribute contains only alphabetic characters.
		 *
		 * @return {boolean}
		 */
		AlphaDash: function(value) {
			if (typeof  value !== 'string') {
				return false;
			}
			var regex = new RegExp("^(?:^[a-z0-9\u00E0-\u00FC_-]+$)$",'i');
			return regex.test(value);
		},

		/**
		 * Validate that an attribute passes a regular expression check.
		 *
		 * @return {boolean}
		 */
		Regex: function(value, element, params) {
			var invalidModifiers=['x','s','u','X','U','A'];
			// Converting php regular expression
			var phpReg= new RegExp('^(?:\/)(.*\\\/?[^\/]*|[^\/]*)(?:\/)([gmixXsuUAJ]*)?$');
			var matches=params[0].match(phpReg);
			if (matches === null) {
				return false;
			}
			// checking modifiers
			var php_modifiers=[];
			if (matches[2]!==undefined) {
				php_modifiers=matches[2].split('');
				for (var i=0; i<php_modifiers.length<i ;i++) {
					if (invalidModifiers.indexOf(php_modifiers[i])!==-1) {
						return true;
					}
				}
			}
			var regex = new RegExp("^(?:"+matches[1]+")$",php_modifiers.join());
			return   regex.test(value);
		},

		/**
		 * Validate that an attribute is a valid date.
		 *
		 * @return {boolean}
		 */
		Date: function(value) {
			return (laravelValidation.helpers.strtotime(value)!==false);
		},

		/**
		 * Validate that an attribute matches a date format.
		 *
		 * @return {boolean}
		 */
		DateFormat: function(value, element, params) {
			return laravelValidation.helpers.parseTime(value,params[0])!==false;
		},

		/**
		 * Validate the date is before a given date.
		 *
		 * @return {boolean}
		 */
		Before: function(value, element, params) {

			var timeCompare=parseFloat(params);
			if (isNaN(timeCompare)) {
				var target=laravelValidation.helpers.dependentElement(this, element, params);
				if (target===undefined) {
					return false;
				}
				timeCompare= laravelValidation.helpers.parseTime(this.elementValue(target), target);
			}

			var timeValue=laravelValidation.helpers.parseTime(value, element);
			return  (timeValue !==false && timeValue < timeCompare);

		},

		/**
		 * Validate the date is after a given date.
		 *
		 * @return {boolean}
		 */
		After: function(value, element, params) {
			var timeCompare=parseFloat(params);
			if (isNaN(timeCompare)) {
				var target=laravelValidation.helpers.dependentElement(this, element, params);
				if (target===undefined) {
					return false;
				}
				timeCompare= laravelValidation.helpers.parseTime(this.elementValue(target), target);
			}

			var timeValue=laravelValidation.helpers.parseTime(value, element);
			return  (timeValue !==false && timeValue > timeCompare);

		},


		/**
		 * Validate that an attribute is a valid date.
		 */
		Timezone: function(value) {
			return  laravelValidation.helpers.isTimezone(value);
		},


		/**
		 * Validate the attribute is a valid JSON string.
		 *
		 * @param  value
		 * @return bool
		 */
		Json: function(value) {
			var result = true;
			try {
				JSON.parse(value);
			} catch (e) {
				result = false;
			}
			return result;
		}
	}
});

//# sourceMappingURL=jsvalidation.js.map