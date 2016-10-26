/**
 * ExcellentExport.
 * A client side Javascript export to Excel.
 *
 * @author: Jordi Burgos (jordiburgos@gmail.com)
 *
 * Based on:
 * https://gist.github.com/insin/1031969
 * http://jsfiddle.net/insin/cmewv/
 *
 * CSV: http://en.wikipedia.org/wiki/Comma-separated_values
 */

/*
 * Base64 encoder/decoder from: http://jsperf.com/base64-optimized
 */
 
 /*
 MIT License

Copyright (c) 2016 Jordi Burgos

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/


var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
var fromCharCode = String.fromCharCode;
var INVALID_CHARACTER_ERR = ( function() {
        // fabricate a suitable error object
        try {
            document.createElement('$');
        } catch (error) {
            return error;
        }
    }());

// encoder
window.btoa || (window.btoa = function(string) {
    var a, b, b1, b2, b3, b4, c, i = 0, len = string.length, max = Math.max, result = '';

    while (i < len) {
        a = string.charCodeAt(i++) || 0;
        b = string.charCodeAt(i++) || 0;
        c = string.charCodeAt(i++) || 0;

        if (max(a, b, c) > 0xFF) {
            throw INVALID_CHARACTER_ERR;
        }

        b1 = (a >> 2) & 0x3F;
        b2 = ((a & 0x3) << 4) | ((b >> 4) & 0xF);
        b3 = ((b & 0xF) << 2) | ((c >> 6) & 0x3);
        b4 = c & 0x3F;

        if (!b) {
            b3 = b4 = 64;
        } else if (!c) {
            b4 = 64;
        }
        result += characters.charAt(b1) + characters.charAt(b2) + characters.charAt(b3) + characters.charAt(b4);
    }
    return result;
});

// decoder
window.atob || (window.atob = function(string) {
    string = string.replace(/=+$/, '');
    var a, b, b1, b2, b3, b4, c, i = 0, len = string.length, chars = [];

    if (len % 4 === 1)
        throw INVALID_CHARACTER_ERR;

    while (i < len) {
        b1 = characters.indexOf(string.charAt(i++));
        b2 = characters.indexOf(string.charAt(i++));
        b3 = characters.indexOf(string.charAt(i++));
        b4 = characters.indexOf(string.charAt(i++));

        a = ((b1 & 0x3F) << 2) | ((b2 >> 4) & 0x3);
        b = ((b2 & 0xF) << 4) | ((b3 >> 2) & 0xF);
        c = ((b3 & 0x3) << 6) | (b4 & 0x3F);

        chars.push(fromCharCode(a));
        b && chars.push(fromCharCode(b));
        c && chars.push(fromCharCode(c));
    }
    return chars.join('');
});


ExcellentExport = (function() {
    var version = "1.3";
    var uri = {excel: 'data:application/vnd.ms-excel;base64,', csv: 'data:application/csv;base64,'};
    var template = {excel: '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'};
    var base64 = function(s) {
        return window.btoa(unescape(encodeURIComponent(s)));
    };
    var format = function(s, c) {
        return s.replace(/{(\w+)}/g, function(m, p) {
            return c[p];
        });
    };

    var get = function(element) {
        if (!element.nodeType) {
            return document.getElementById(element);
        }
        return element;
    };
	
	
    var fixCSVField = function(value) {
        var fixedValue = value;
        var addQuotes = (value.indexOf(',') !== -1) || (value.indexOf('\r') !== -1) || (value.indexOf('\n') !== -1);
        var replaceDoubleQuotes = (value.indexOf('"') !== -1);


		//clean up html tags
		fixedValue= fixedValue.replace(/<div class\=\"radio\"[^>]*>|<\/div>/gi, "");
		fixedValue= fixedValue.replace(/<label>(.)*<\/label>/gi, "");
		fixedValue= fixedValue.replace(/<input[^>]*>|<\/input>/gi, "");
		fixedValue= fixedValue.replace(/<div style=\"display:none;\">/gi, "");
		fixedValue= fixedValue.replace(/<span[^>]*>|<\/span>/gi, "");
		//clunkily remove catalog links
		fixedValue= fixedValue.replace(/<a(.)*<\/a>/gi, "");
	    fixedValue= fixedValue.replace(/Catalog Links/gi, "");


        if (replaceDoubleQuotes) {
            fixedValue = fixedValue.replace(/"/g, '""');
        }
        if (addQuotes || replaceDoubleQuotes) {
            fixedValue = '"' + fixedValue + '"';
        }
		
        return fixedValue;
    };
	
	

    var tableToCSV = function(table) {
        var data = "";
        for (var i = 0, row; row = table.rows[i]; i++) {
            for (var j = 0, col; col = row.cells[j]; j++) {
                data = data + (j ? ',' : '') + fixCSVField(col.innerHTML);
            }
            data = data + "\r\n";
        }
        return data;
    };

    var ee = {
        /** @expose */
        excel: function(anchor, table, name) {
            table = get(table);
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML};
            var hrefvalue = uri.excel + base64(format(template.excel, ctx));
            anchor.href = hrefvalue;
            // Return true to allow the link to work
            return true;
        },
        /** @expose */
        csv: function(anchor, table) {
            table = get(table);
            var csvData = tableToCSV(table);
            var hrefvalue = uri.csv + base64(csvData);
            anchor.href = hrefvalue;
            return true;
        }
    };

    return ee;
}());
