// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: http://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../lib/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../lib/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMode("verb", function(conf, parserConf) {
    var ERRORCLASS = 'error';

    function wordRegexp(words) {
        return new RegExp("^((" + words.join(")|(") + "))\\b", "i");
    }

    var commonkeywords = loadKeywords();
    
    var keywords = wordRegexp(commonkeywords);
    
    // tokenizers
    function tokenize(stream, state) {
        if (stream.eatSpace()) {
            return null;
        }

        if (stream.match(/^\w/, false)){
            if (stream.match(keywords)) {
                return 'keyword';
            }
            
            stream.eatWhile(/[^\s]/);
            return null;
        }
        
        stream.next();
        return null;
    }
    
    console.log("completed");

    return {
    	token: tokenize
    }
});

CodeMirror.defineMIME("text/x-verb", "verb");

});
