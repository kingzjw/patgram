function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}

var VERB = {};
VERB.KEYWORDS = {};

VERB.getPastForm = function (value) {
	if(endsWith(value, "e")) {
		return value + "d";
	}
	else {
		return value + "ed";
	}
};

VERB.getProgressiveForm = function (value) {
	if(endsWith(value, "e")) {
		return value.substring(0, value.length-1) + "ing";
	}
	else {
		return value + "ing";
	}
};

VERB.getThirdPersonForm = function (value) {
	if(value=="have") {
		return "has";
	}
	else if(/(o|x|ch|sh|ss)$/.test(value)) {
		return value + "es";
	}
	else if(/[bcdfghjklmnpqrstvwxyz]y$/.test(value)) {
		return value.substring(0, value.length-1) + "ies";
	}
	else {
		return value + "s";
	}
};

VERB.getNormalForm = function (value) {
//	if(endsWith(value, "ing")) {
//		var temp = value.substring(0, value.length-3);
//		if(VERB.KEYWORDS[temp]==1) {
//			// e.g. eating
//			return temp;
//		}
//		temp = temp + "e";
//		if(VERB.KEYWORDS[temp]==1) {
//			// e.g. making
//			return temp;
//		}
//	}
//	else if(endsWith(value, "ed")) {
//		var temp = value.substring(0, value.length-1);
//		if(VERB.KEYWORDS[temp]==1) {
//			// e.g. created
//			return temp;
//		}
//		temp = temp.substring(0, temp.length-1);
//		if(VERB.KEYWORDS[temp]==1) {
//			// e.g. walked
//			return temp;
//		}
//		if(endsWith(temp, "i")) {
//			temp = value.substring(0, temp.length-1) + "y";
//			if(VERB.KEYWORDS[temp]==1) {
//				// e.g. flied
//				return temp;
//			}
//		}
//	}
//	else if(endsWith(value, "s")) {
//		var temp = value.substring(0, value.length-1);
//		if(VERB.KEYWORDS[temp]==1) {
//			// e.g calls
//			return temp;
//		}
//		if(endsWith(temp, "se")) {
//			temp = value.substring(0, temp.length-1);
//			if(VERB.KEYWORDS[temp]==1) {
//				// e.g. guesses
//				return temp;
//			}
//		}
//		if(endsWith(temp, "ie")) {
//			temp = value.substring(0, temp.length-2) + "y";
//			if(VERB.KEYWORDS[temp]==1) {
//				// e.g. flies
//				return temp;
//			}
//		}
//	}
	
	return VERB.KEYWORDS[value];
};

VERB.isKeyword = function (value) {
	return VERB.KEYWORDS.hasOwnProperty(value);
};

function loadKeywords() {
	var irregulars = loadIrregulars();
	
	var keywords = [];
	var lines = loadKeywordsFrom();
	$.each(lines, function(index, value) {
		keywords.push(value);
		VERB.KEYWORDS[value] = value;
		
		if(value.indexOf("_")==-1) {
			var thirdPerson = VERB.getThirdPersonForm(value);
			keywords.push(thirdPerson);
			VERB.KEYWORDS[thirdPerson] = value;
		
			if(irregulars.hasOwnProperty(value)) {
				var forms = irregulars[value].split(" ");
				$.each(forms, function(index, form) {
					keywords.push(form);
					VERB.KEYWORDS[form] = value;
				});
			}
			else {
				var past = VERB.getPastForm(value);
				keywords.push(past);
				VERB.KEYWORDS[past] = value;
				
				var progressive = VERB.getProgressiveForm(value);
				keywords.push(progressive);
				VERB.KEYWORDS[progressive] = value;
			}
		}
	});
	
	console.log("parse completed");
	return keywords;
}

function loadIrregulars() {
	var irregulars = {};
	$.ajax({
		type : "GET",
		url : window.location.protocol +"//"+ window.location.host +"/glossary/irregular.txt",
		async : false,
		success : function(data) {
			console.log("download irregular completed");
			
			var lines = data.split("\n");
			$.each(lines, function(index, value) {
				if(/(\w+): (\w.*)/.test(value)) {
					irregulars[RegExp.$1] = RegExp.$2;
				}
			});
		}
	});

	return irregulars;
}

function templateHightlightVerbInSentence(items, verbs, options) {
	var out = "";

	var irregulars = loadIrregulars();			
	var keywords = [];
	$.each(verbs, function(index, value) {
		keywords[value.ev] = value.ev;
		
		if(value.ev.indexOf("_")==-1) {
			var thirdPerson = VERB.getThirdPersonForm(value.ev);
			keywords[thirdPerson] = value.ev;
		
			if(irregulars.hasOwnProperty(value.ev)) {
				var forms = irregulars[value.ev].split(" ");
				$.each(forms, function(index, form) {
					keywords[form] = value.ev;
				});
			}
			else {
				var past = VERB.getPastForm(value.ev);
				keywords[past] = value.ev;
				
				var progressive = VERB.getProgressiveForm(value.ev);
				keywords[progressive] = value.ev;
			}
		}
	});
	
	$.each(items, function (index, value) {
		var regex = /(\w+)|([^\w]+)/g;
		var sentence = "";
		
		while(match = regex.exec(value)) {
			if(keywords.hasOwnProperty(match[0].toLowerCase())) {
				sentence = sentence + options.fn(match[0]);
			}
			else {
				sentence = sentence + match[0];
			}
		}

		out = out + "<li>" + sentence +"</li>";
	});
	
	return out;
}

function templateTablebody(items, items_per_row, options) {
	var out = "";
	var length = items.length;
	
	$.each(items, function (index, value) {
		if(index%items_per_row==0) {
			out = out + "<tr>";
		}
		out = out + "<td>" + options.fn(value) + "</td>";
		if(index%items_per_row==items_per_row-1) {
			out = out + "</tr>";
		}
	});

	var comp = items_per_row-length%items_per_row;
	if(comp != items_per_row){
		for(var i=0; i<comp; i++){
			out = out + "<td></td>";
		}
		out = out + "</tr>";
	}

	return out;
}
