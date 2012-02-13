/**
 * HeatMap Table Generator
 *
 * Copyright (c) 2011 Fazmin Nizam
 * 
 *
 * v1.1 - 2011-12-03 - First release.
 */
(function ($) {
  var k = '1.0';
  var l = function (a) {
      return Math.max.apply(Math, a)
    };
  var m = function (a) {
      return Math.min.apply(Math, a)
    };
  var n = function (a, b) {
      if (b.length == 1 || a <= 0) {
        var c = b[0]
      } else if (a >= 100) {
        var c = b[b.length - 1]
      } else {
        var d = 100 / (b.length - 1);
        for (i = 0, j = 0; i < 100; i += d, j++) {
          if (a >= i && a <= i + d) break
        }
        var e = b[j];
        var f = b[j + 1];
        var g = (a - i) / d;
        var c = [Math.max(Math.min(parseInt((g * (f[0] - e[0])) + e[0]), 255), 0), Math.max(Math.min(parseInt((g * (f[1] - e[1])) + e[1]), 255), 0), Math.max(Math.min(parseInt((g * (f[2] - e[2])) + e[2]), 255), 0)]
      }
      return c
    };
  $.fn.colorheat = function (d) {
    var o = $.extend({}, $.fn.colorheat.defaults, d);
    var e = $.fn.colorheat.cleaners[o.cleaner];
    var f = $.fn.colorheat.painters[o.painter];
    if (!$.isFunction(e) || !$.isFunction(f)) return this;
    if (o.colorMap.constructor != Array) {
      o.colorMap = $.fn.colorheat.colorMaps[o.colorMap]
    }
    var g = [];
    this.each(function () {
      var a = $(this);
      var b = e(a.text(), o);
      if (isNaN(b) && o.defaultValue !== null) {
        b = Number(o.defaultValue)
      }
      a.data('value', b);
      if (!isNaN(b)) {
        g.push(b)
      }
    });
    var h = (o.max === null) ? l(g) : Number(o.max);
    var i = (o.min === null) ? m(g) : Number(o.min);
    g = null;
    this.each(function () {
      var a = $(this);
      var b = a.data('value');
      //alert(b+'-----'+a);
      if (isNaN(b)) return true;
      var c = (b - i) / (h - i) * 100;
      a.data('percent', c);
      if ($.isFunction(o.callBeforePaint)) {
        o.callBeforePaint.call(a)
      }
      f(a, o)
    });
    return this
  };
  $.fn.colorheat.cleaners = {};

  //Anything above displayed in a different color
  $.fn.colorheat.cleaners.basic = function (a, o) {
    if (o.decimalPoint == ',') {
      a = a.replace(/,/g, '.')
    }
    return parseFloat(a)
  };
  
  //Calculate log values
  $.fn.colorheat.cleaners.log = function (a, o) {
      a = Math.log(a);
	  if (a<0){
	  a=0;
	  } else {}
    return parseFloat(a)
  };
  
  $.fn.colorheat.cleaners.strip = function (a, o) {
    a = a.replace(o.decimalPoint == '.' ? /[^-\d.]+/g : /[^-\d,]+/g, '');
    return $.fn.colorheat.cleaners.basic(a, o)
  };
  $.fn.colorheat.cleaners.seconds = function (a, o) {
    var b = (o.decimalPoint == '.') ? /\b(?:(\d+):)?(\d\d?):(\d\d(?:\.\d+)?)\b/ : /\b(?:(\d+):)?(\d\d?):(\d\d(?:,\d+)?)\b/;
    if (!(result = b.exec(a))) return NaN;
    if (result[1] === undefined) {
      result[1] = 0
    }
    return parseInt(result[1]) * 3600 + parseInt(result[2]) * 60 + parseFloat(result[3])
  };
  $.fn.colorheat.cleaners.minutes = function (a, o) {
    if (!(result = /\b(\d+):(\d\d)\b/.exec(a))) return NaN;
    return parseInt(result[1]) * 3600 + parseInt(result[2]) * 60
  };
  $.fn.colorheat.painters = {};
  $.fn.colorheat.painters.bars = function (a, o) {
    var b = a.data('percent');
    var c = n(b, o.colorMap);
    switch (o.barsAlign) {
    case 'hcenter':
      var d = 'top:0; bottom:0; left:' + (100 - b) / 2 + '%; width:' + b + '%;';
      break;
    case 'vcenter':
      var d = 'top:' + (100 - b) / 2 + '%; right:0; left:0; height:' + b + '%;';
      break;
    case 'top':
      var d = 'top:0; right:0; left:0; height:' + b + '%;';
      break;
    case 'right':
      var d = 'top:0; right:0; bottom:0; width:' + b + '%;';
      break;
    case 'bottom':
      var d = 'right:0; bottom:0; left:0; height:' + b + '%;';
      break;
    default:
      var d = 'top:0; bottom:0; left:0; width:' + b + '%;'
    }
    a.html('<div style="position:relative; padding:' + a.css('padding-top') + ' ' + a.css('padding-right') + ' ' + a.css('padding-bottom') + ' ' + a.css('padding-left') + '; height:' + a.height() + 'px;">' + '<div class="' + o.classBar + '" style="position:absolute; ' + d + ' background-color:rgb(' + c + '); z-index:1;"></div>' + '<div style="position:relative; z-index:2;">' + a.html() + '</div>' + '</div>').css('padding', 0)
  };
  $.fn.colorheat.painters.bubbles = function (a, o) {
    var b = a.data('percent');
    var c = n(b, o.colorMap);
    var d = (o.bubblesDiameter === null) ? a.innerHeight() * 2 : Number(o.bubblesDiameter);
    a.html('<div style="position:relative; padding:' + a.css('padding-top') + ' ' + a.css('padding-right') + ' ' + a.css('padding-bottom') + ' ' + a.css('padding-left') + '; height:' + a.height() + 'px;">' + '<div class="' + o.classBubble + '" style="position:absolute; top:' + (a.innerHeight() / 2 - b / 100 * d / 2) + 'px; left:' + (a.innerWidth() / 2 - b / 100 * d / 2) + 'px; width:' + b / 100 * d + 'px; height:' + b / 100 * d + 'px; background:rgb(' + c + '); opacity:0.75; z-index:' + Math.round(b) + '; border-radius:999px; -moz-border-radius:999px; -webkit-border-radius:999px;"></div>' + '<div style="position:relative; z-index:102;" class="intextv">' + a.html() + '</div>' + '</div>').css('padding', 0)
  };
  
  //Circular Log based
  $.fn.colorheat.painters.circlelog = function (a, o) {
    var b = a.data('percent');
    var c = n(b, o.colorMap);
    var d = (o.bubblesDiameter === null) ? a.innerHeight() * 2 : Number(o.bubblesDiameter);
	var indata =Math.round(Math.log(a.html()));
	if (indata=='-Infinity'){
	indata='';
	}
    a.html('<div style="position:relative; padding:' + a.css('padding-top') + ' ' + a.css('padding-right') + ' ' + a.css('padding-bottom') + ' ' + a.css('padding-left') + '; height:' + a.height() + 'px;">' + '<div class="' + o.classBubble + '" style="position:absolute; top:' + (a.innerHeight() / 2 - b / 100 * d / 2) + 'px; left:' + (a.innerWidth() / 2 - b / 100 * d / 2) + 'px; width:' + b / 100 * d + 'px; height:' + b / 100 * d + 'px; background:rgb(' + c + '); opacity:0.75; z-index:' + Math.round(b) + '; border-radius:999px; -moz-border-radius:999px; -webkit-border-radius:999px;"></div>' + '<div class="intextv" style="position:relative; z-index:102;">' + indata + '</div>' + '</div>').css('padding', 0)
  };
  
  $.fn.colorheat.painters.fill = function (a, o) {
    var b = a.data('percent');
    var c = n(b, o.colorMap);
    a.css('background-color', 'rgb(' + c + ')')
  };

  $.fn.colorheat.painters.solo = function (a, o) {
    if($('#cut_val').val()=='') {
        var cutval =100;
    } else{
        var cutval =$('#cut_val').val();
    }
    
    if($('#cut_col').val()=='') {
        var cutcol ='#0000CC';
    } else{
        var cutcol =$('#cut_col').val();
    }
    var b = a.data('percent');
    var m = a.data('value');
    if (m <= cutval) {
      var c = n(b, o.colorMap);
      a.css('background-color', 'rgb(' + c + ')')
      a.css('color', 'rgb(' + c + ')')
      a.css('font-size', '1px')
      a.css('width', '30px')
    } else {
      var c = n(b, o.colorMap);
      a.css('background-color', '#'+cutcol)
      a.css('color', '#'+cutcol)
      a.css('font-size', '1px')
      a.css('width', '30px')
      //a.css('border','0px solid black')
    }
  };

  $.fn.colorheat.version = function () {
    return k
  };
  $.fn.colorheat.colorMaps = {
    burn: [
      [246, 233, 24],
      [203, 19, 32]
    ],
    grayPower: [
      [229, 229, 299],
      [26, 26, 26]
    ],
    greenPower: [
      [245, 248, 221],
      [198, 224, 142],
      [64, 175, 94],
      [9, 74, 36]
    ],
    heatmap: [
      [143, 217, 16],
      [246, 233, 24],
      [203, 19, 32]
    ],
    thermometer: [
      [23, 54, 125],
      [121, 190, 213],
      [214, 228, 231],
      [242, 146, 133],
      [203, 19, 32]
    ],
    burnit: [
      [30, 30, 30],
      [255, 255, 255],
      [255, 25, 25]
    ]
  };
  $.fn.colorheat.defaults = {
    barsAlign: 'left',
    bubblesDiameter: null,
    callBeforePaint: null,
    classBar: 'bar',
    classBubble: 'fill',
    cleaner: 'basic',
    colorMap: 'heatmap',
    decimalPoint: '.',
    defaultValue: null,
    max: null,
    min: null,
    painter: 'fill'
  }
})(jQuery);