(function (global, factory) {
      typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
            typeof define === 'function' && define.amd ? define(['exports'], factory) :
                  (global = global || self, factory(global.window = global.window || {}));
}(this, (function (exports) {
      'use strict';

      /*!
       * ScrollTrigger 3.7.1
       * https://greensock.com
       *
       * @license Copyright 2008-2021, GreenSock. All rights reserved.
       * Subject to the terms at https://greensock.com/standard-license or for
       * Club GreenSock members, the agreement issued with that membership.
       * @author: Jack Doyle, jack@greensock.com
      */
      var gsap,
            _coreInitted,
            _win,
            _doc,
            _docEl,
            _body,
            _root,
            _resizeDelay,
            _raf,
            _request,
            _toArray,
            _clamp,
            _time2,
            _syncInterval,
            _refreshing,
            _pointerIsDown,
            _transformProp,
            _i,
            _prevWidth,
            _prevHeight,
            _autoRefresh,
            _sort,
            _suppressOverwrites,
            _ignoreResize,
            _limitCallbacks,
            _startup = 1,
            _proxies = [],
            _scrollers = [],
            _getTime = Date.now,
            _time1 = _getTime(),
            _lastScrollTime = 0,
            _enabled = 1,
            _passThrough = function _passThrough(v) {
                  return v;
            },
            _round = function _round(value) {
                  return Math.round(value * 100000) / 100000 || 0;
            },
            _windowExists = function _windowExists() {
                  return typeof window !== "undefined";
            },
            _getGSAP = function _getGSAP() {
                  return gsap || _windowExists() && (gsap = window.gsap) && gsap.registerPlugin && gsap;
            },
            _isViewport = function _isViewport(e) {
                  return !!~_root.indexOf(e);
            },
            _getProxyProp = function _getProxyProp(element, property) {
                  return ~_proxies.indexOf(element) && _proxies[_proxies.indexOf(element) + 1][property];
            },
            _getScrollFunc = function _getScrollFunc(element, _ref) {
                  var s = _ref.s,
                        sc = _ref.sc;

                  var i = _scrollers.indexOf(element),
                        offset = sc === _vertical.sc ? 1 : 2;

                  !~i && (i = _scrollers.push(element) - 1);
                  return _scrollers[i + offset] || (_scrollers[i + offset] = _getProxyProp(element, s) || (_isViewport(element) ? sc : function (value) {
                        return arguments.length ? element[s] = value : element[s];
                  }));
            },
            _getBoundsFunc = function _getBoundsFunc(element) {
                  return _getProxyProp(element, "getBoundingClientRect") || (_isViewport(element) ? function () {
                        _winOffsets.width = _win.innerWidth;
                        _winOffsets.height = _win.innerHeight;
                        return _winOffsets;
                  } : function () {
                        return _getBounds(element);
                  });
            },
            _getSizeFunc = function _getSizeFunc(scroller, isViewport, _ref2) {
                  var d = _ref2.d,
                        d2 = _ref2.d2,
                        a = _ref2.a;
                  return (a = _getProxyProp(scroller, "getBoundingClientRect")) ? function () {
                        return a()[d];
                  } : function () {
                        return (isViewport ? _win["inner" + d2] : scroller["client" + d2]) || 0;
                  };
            },
            _getOffsetsFunc = function _getOffsetsFunc(element, isViewport) {
                  return !isViewport || ~_proxies.indexOf(element) ? _getBoundsFunc(element) : function () {
                        return _winOffsets;
                  };
            },
            _maxScroll = function _maxScroll(element, _ref3) {
                  var s = _ref3.s,
                        d2 = _ref3.d2,
                        d = _ref3.d,
                        a = _ref3.a;
                  return (s = "scroll" + d2) && (a = _getProxyProp(element, s)) ? a() - _getBoundsFunc(element)()[d] : _isViewport(element) ? Math.max(_docEl[s], _body[s]) - (_win["inner" + d2] || _docEl["client" + d2] || _body["client" + d2]) : element[s] - element["offset" + d2];
            },
            _iterateAutoRefresh = function _iterateAutoRefresh(func, events) {
                  for (var i = 0; i < _autoRefresh.length; i += 3) {
                        (!events || ~events.indexOf(_autoRefresh[i + 1])) && func(_autoRefresh[i], _autoRefresh[i + 1], _autoRefresh[i + 2]);
                  }
            },
            _isString = function _isString(value) {
                  return typeof value === "string";
            },
            _isFunction = function _isFunction(value) {
                  return typeof value === "function";
            },
            _isNumber = function _isNumber(value) {
                  return typeof value === "number";
            },
            _isObject = function _isObject(value) {
                  return typeof value === "object";
            },
            _callIfFunc = function _callIfFunc(value) {
                  return _isFunction(value) && value();
            },
            _combineFunc = function _combineFunc(f1, f2) {
                  return function () {
                        var result1 = _callIfFunc(f1),
                              result2 = _callIfFunc(f2);

                        return function () {
                              _callIfFunc(result1);

                              _callIfFunc(result2);
                        };
                  };
            },
            _abs = Math.abs,
            _scrollLeft = "scrollLeft",
            _scrollTop = "scrollTop",
            _left = "left",
            _top = "top",
            _right = "right",
            _bottom = "bottom",
            _width = "width",
            _height = "height",
            _Right = "Right",
            _Left = "Left",
            _Top = "Top",
            _Bottom = "Bottom",
            _padding = "padding",
            _margin = "margin",
            _Width = "Width",
            _Height = "Height",
            _px = "px",
            _horizontal = {
                  s: _scrollLeft,
                  p: _left,
                  p2: _Left,
                  os: _right,
                  os2: _Right,
                  d: _width,
                  d2: _Width,
                  a: "x",
                  sc: function sc(value) {
                        return arguments.length ? _win.scrollTo(value, _vertical.sc()) : _win.pageXOffset || _doc[_scrollLeft] || _docEl[_scrollLeft] || _body[_scrollLeft] || 0;
                  }
            },
            _vertical = {
                  s: _scrollTop,
                  p: _top,
                  p2: _Top,
                  os: _bottom,
                  os2: _Bottom,
                  d: _height,
                  d2: _Height,
                  a: "y",
                  op: _horizontal,
                  sc: function sc(value) {
                        return arguments.length ? _win.scrollTo(_horizontal.sc(), value) : _win.pageYOffset || _doc[_scrollTop] || _docEl[_scrollTop] || _body[_scrollTop] || 0;
                  }
            },
            _getComputedStyle = function _getComputedStyle(element) {
                  return _win.getComputedStyle(element);
            },
            _makePositionable = function _makePositionable(element) {
                  var position = _getComputedStyle(element).position;

                  element.style.position = position === "absolute" || position === "fixed" ? position : "relative";
            },
            _setDefaults = function _setDefaults(obj, defaults) {
                  for (var p in defaults) {
                        p in obj || (obj[p] = defaults[p]);
                  }

                  return obj;
            },
            _getBounds = function _getBounds(element, withoutTransforms) {
                  var tween = withoutTransforms && _getComputedStyle(element)[_transformProp] !== "matrix(1, 0, 0, 1, 0, 0)" && gsap.to(element, {
                        x: 0,
                        y: 0,
                        xPercent: 0,
                        yPercent: 0,
                        rotation: 0,
                        rotationX: 0,
                        rotationY: 0,
                        scale: 1,
                        skewX: 0,
                        skewY: 0
                  }).progress(1),
                        bounds = element.getBoundingClientRect();
                  tween && tween.progress(0).kill();
                  return bounds;
            },
            _getSize = function _getSize(element, _ref4) {
                  var d2 = _ref4.d2;
                  return element["offset" + d2] || element["client" + d2] || 0;
            },
            _getLabelRatioArray = function _getLabelRatioArray(timeline) {
                  var a = [],
                        labels = timeline.labels,
                        duration = timeline.duration(),
                        p;

                  for (p in labels) {
                        a.push(labels[p] / duration);
                  }

                  return a;
            },
            _getClosestLabel = function _getClosestLabel(animation) {
                  return function (value) {
                        return gsap.utils.snap(_getLabelRatioArray(animation), value);
                  };
            },
            _getLabelAtDirection = function _getLabelAtDirection(timeline) {
                  return function (value, st) {
                        var a = _getLabelRatioArray(timeline),
                              i;

                        a.sort(function (a, b) {
                              return a - b;
                        });

                        if (st.direction > 0) {
                              value -= 1e-4;

                              for (i = 0; i < a.length; i++) {
                                    if (a[i] >= value) {
                                          return a[i];
                                    }
                              }

                              return a.pop();
                        } else {
                              i = a.length;
                              value += 1e-4;

                              while (i--) {
                                    if (a[i] <= value) {
                                          return a[i];
                                    }
                              }
                        }

                        return a[0];
                  };
            },
            _multiListener = function _multiListener(func, element, types, callback) {
                  return types.split(",").forEach(function (type) {
                        return func(element, type, callback);
                  });
            },
            _addListener = function _addListener(element, type, func) {
                  return element.addEventListener(type, func, {
                        passive: true
                  });
            },
            _removeListener = function _removeListener(element, type, func) {
                  return element.removeEventListener(type, func);
            },
            _markerDefaults = {
                  startColor: "green",
                  endColor: "red",
                  indent: 0,
                  fontSize: "16px",
                  fontWeight: "normal"
            },
            _defaults = {
                  toggleActions: "play",
                  anticipatePin: 0
            },
            _keywords = {
                  top: 0,
                  left: 0,
                  center: 0.5,
                  bottom: 1,
                  right: 1
            },
            _offsetToPx = function _offsetToPx(value, size) {
                  if (_isString(value)) {
                        var eqIndex = value.indexOf("="),
                              relative = ~eqIndex ? +(value.charAt(eqIndex - 1) + 1) * parseFloat(value.substr(eqIndex + 1)) : 0;

                        if (~eqIndex) {
                              value.indexOf("%") > eqIndex && (relative *= size / 100);
                              value = value.substr(0, eqIndex - 1);
                        }

                        value = relative + (value in _keywords ? _keywords[value] * size : ~value.indexOf("%") ? parseFloat(value) * size / 100 : parseFloat(value) || 0);
                  }

                  return value;
            },
            _createMarker = function _createMarker(type, name, container, direction, _ref5, offset, matchWidthEl) {
                  var startColor = _ref5.startColor,
                        endColor = _ref5.endColor,
                        fontSize = _ref5.fontSize,
                        indent = _ref5.indent,
                        fontWeight = _ref5.fontWeight;

                  var e = _doc.createElement("div"),
                        useFixedPosition = _isViewport(container) || _getProxyProp(container, "pinType") === "fixed",
                        isScroller = type.indexOf("scroller") !== -1,
                        parent = useFixedPosition ? _body : container,
                        isStart = type.indexOf("start") !== -1,
                        color = isStart ? startColor : endColor,
                        css = "border-color:" + color + ";font-size:" + fontSize + ";color:" + color + ";font-weight:" + fontWeight + ";pointer-events:none;white-space:nowrap;font-family:sans-serif,Arial;z-index:1000;padding:4px 8px;border-width:0;border-style:solid;";

                  css += "position:" + (isScroller && useFixedPosition ? "fixed;" : "absolute;");
                  (isScroller || !useFixedPosition) && (css += (direction === _vertical ? _right : _bottom) + ":" + (offset + parseFloat(indent)) + "px;");
                  matchWidthEl && (css += "box-sizing:border-box;text-align:left;width:" + matchWidthEl.offsetWidth + "px;");
                  e._isStart = isStart;
                  e.setAttribute("class", "gsap-marker-" + type);
                  e.style.cssText = css;
                  e.innerText = name || name === 0 ? type + "-" + name : type;
                  parent.children[0] ? parent.insertBefore(e, parent.children[0]) : parent.appendChild(e);
                  e._offset = e["offset" + direction.op.d2];

                  _positionMarker(e, 0, direction, isStart);

                  return e;
            },
            _positionMarker = function _positionMarker(marker, start, direction, flipped) {
                  var vars = {
                        display: "block"
                  },
                        side = direction[flipped ? "os2" : "p2"],
                        oppositeSide = direction[flipped ? "p2" : "os2"];
                  marker._isFlipped = flipped;
                  vars[direction.a + "Percent"] = flipped ? -100 : 0;
                  vars[direction.a] = flipped ? "1px" : 0;
                  vars["border" + side + _Width] = 1;
                  vars["border" + oppositeSide + _Width] = 0;
                  vars[direction.p] = start + "px";
                  gsap.set(marker, vars);
            },
            _triggers = [],
            _ids = {},
            _sync = function _sync() {
                  return _request || (_request = _raf(_updateAll));
            },
            _onScroll = function _onScroll() {
                  if (!_request) {
                        _request = _raf(_updateAll);
                        _lastScrollTime || _dispatch("scrollStart");
                        _lastScrollTime = _getTime();
                  }
            },
            _onResize = function _onResize() {
                  return !_refreshing && !_ignoreResize && !_doc.fullscreenElement && _resizeDelay.restart(true);
            },
            _listeners = {},
            _emptyArray = [],
            _media = [],
            _creatingMedia,
            _lastMediaTick,
            _onMediaChange = function _onMediaChange(e) {
                  var tick = gsap.ticker.frame,
                        matches = [],
                        i = 0,
                        index;

                  if (_lastMediaTick !== tick || _startup) {
                        _revertAll();

                        for (; i < _media.length; i += 4) {
                              index = _win.matchMedia(_media[i]).matches;

                              if (index !== _media[i + 3]) {
                                    _media[i + 3] = index;
                                    index ? matches.push(i) : _revertAll(1, _media[i]) || _isFunction(_media[i + 2]) && _media[i + 2]();
                              }
                        }

                        _revertRecorded();

                        for (i = 0; i < matches.length; i++) {
                              index = matches[i];
                              _creatingMedia = _media[index];
                              _media[index + 2] = _media[index + 1](e);
                        }

                        _creatingMedia = 0;
                        _coreInitted && _refreshAll(0, 1);
                        _lastMediaTick = tick;

                        _dispatch("matchMedia");
                  }
            },
            _softRefresh = function _softRefresh() {
                  return _removeListener(ScrollTrigger, "scrollEnd", _softRefresh) || _refreshAll(true);
            },
            _dispatch = function _dispatch(type) {
                  return _listeners[type] && _listeners[type].map(function (f) {
                        return f();
                  }) || _emptyArray;
            },
            _savedStyles = [],
            _revertRecorded = function _revertRecorded(media) {
                  for (var i = 0; i < _savedStyles.length; i += 5) {
                        if (!media || _savedStyles[i + 4] === media) {
                              _savedStyles[i].style.cssText = _savedStyles[i + 1];
                              _savedStyles[i].getBBox && _savedStyles[i].setAttribute("transform", _savedStyles[i + 2] || "");
                              _savedStyles[i + 3].uncache = 1;
                        }
                  }
            },
            _revertAll = function _revertAll(kill, media) {
                  var trigger;

                  for (_i = 0; _i < _triggers.length; _i++) {
                        trigger = _triggers[_i];

                        if (!media || trigger.media === media) {
                              if (kill) {
                                    trigger.kill(1);
                              } else {
                                    trigger.revert();
                              }
                        }
                  }

                  media && _revertRecorded(media);
                  media || _dispatch("revert");
            },
            _refreshingAll,
            _refreshAll = function _refreshAll(force, skipRevert) {
                  if (_lastScrollTime && !force) {
                        _addListener(ScrollTrigger, "scrollEnd", _softRefresh);

                        return;
                  }

                  _refreshingAll = true;

                  var refreshInits = _dispatch("refreshInit");

                  _sort && ScrollTrigger.sort();
                  skipRevert || _revertAll();

                  _triggers.forEach(function (t) {
                        return t.refresh();
                  });

                  refreshInits.forEach(function (result) {
                        return result && result.render && result.render(-1);
                  });

                  _scrollers.forEach(function (obj) {
                        return typeof obj === "function" && (obj.rec = 0);
                  });

                  _resizeDelay.pause();

                  _refreshingAll = false;

                  _dispatch("refresh");
            },
            _lastScroll = 0,
            _direction = 1,
            _updateAll = function _updateAll() {
                  if (!_refreshingAll) {
                        var l = _triggers.length,
                              time = _getTime(),
                              recordVelocity = time - _time1 >= 50,
                              scroll = l && _triggers[0].scroll();

                        _direction = _lastScroll > scroll ? -1 : 1;
                        _lastScroll = scroll;

                        if (recordVelocity) {
                              if (_lastScrollTime && !_pointerIsDown && time - _lastScrollTime > 200) {
                                    _lastScrollTime = 0;

                                    _dispatch("scrollEnd");
                              }

                              _time2 = _time1;
                              _time1 = time;
                        }

                        if (_direction < 0) {
                              _i = l;

                              while (_i-- > 0) {
                                    _triggers[_i] && _triggers[_i].update(0, recordVelocity);
                              }

                              _direction = 1;
                        } else {
                              for (_i = 0; _i < l; _i++) {
                                    _triggers[_i] && _triggers[_i].update(0, recordVelocity);
                              }
                        }

                        _request = 0;
                  }
            },
            _propNamesToCopy = [_left, _top, _bottom, _right, _margin + _Bottom, _margin + _Right, _margin + _Top, _margin + _Left, "display", "flexShrink", "float", "zIndex", "grid-column-start", "grid-column-end", "grid-row-start", "grid-row-end", "grid-area", "justify-self", "align-self", "place-self"],
            _stateProps = _propNamesToCopy.concat([_width, _height, "boxSizing", "max" + _Width, "max" + _Height, "position", _margin, _padding, _padding + _Top, _padding + _Right, _padding + _Bottom, _padding + _Left]),
            _swapPinOut = function _swapPinOut(pin, spacer, state) {
                  _setState(state);

                  if (pin.parentNode === spacer) {
                        var parent = spacer.parentNode;

                        if (parent) {
                              parent.insertBefore(pin, spacer);
                              parent.removeChild(spacer);
                        }
                  }
            },
            _swapPinIn = function _swapPinIn(pin, spacer, cs, spacerState) {
                  if (pin.parentNode !== spacer) {
                        var i = _propNamesToCopy.length,
                              spacerStyle = spacer.style,
                              pinStyle = pin.style,
                              p;

                        while (i--) {
                              p = _propNamesToCopy[i];
                              spacerStyle[p] = cs[p];
                        }

                        spacerStyle.position = cs.position === "absolute" ? "absolute" : "relative";
                        cs.display === "inline" && (spacerStyle.display = "inline-block");
                        pinStyle[_bottom] = pinStyle[_right] = "auto";
                        spacerStyle.overflow = "visible";
                        spacerStyle.boxSizing = "border-box";
                        spacerStyle[_width] = _getSize(pin, _horizontal) + _px;
                        spacerStyle[_height] = _getSize(pin, _vertical) + _px;
                        spacerStyle[_padding] = pinStyle[_margin] = pinStyle[_top] = pinStyle[_left] = "0";

                        _setState(spacerState);

                        pinStyle[_width] = pinStyle["max" + _Width] = cs[_width];
                        pinStyle[_height] = pinStyle["max" + _Height] = cs[_height];
                        pinStyle[_padding] = cs[_padding];
                        pin.parentNode.insertBefore(spacer, pin);
                        spacer.appendChild(pin);
                  }
            },
            _capsExp = /([A-Z])/g,
            _setState = function _setState(state) {
                  if (state) {
                        var style = state.t.style,
                              l = state.length,
                              i = 0,
                              p,
                              value;
                        (state.t._gsap || gsap.core.getCache(state.t)).uncache = 1;

                        for (; i < l; i += 2) {
                              value = state[i + 1];
                              p = state[i];

                              if (value) {
                                    style[p] = value;
                              } else if (style[p]) {
                                    style.removeProperty(p.replace(_capsExp, "-$1").toLowerCase());
                              }
                        }
                  }
            },
            _getState = function _getState(element) {
                  var l = _stateProps.length,
                        style = element.style,
                        state = [],
                        i = 0;

                  for (; i < l; i++) {
                        state.push(_stateProps[i], style[_stateProps[i]]);
                  }

                  state.t = element;
                  return state;
            },
            _copyState = function _copyState(state, override, omitOffsets) {
                  var result = [],
                        l = state.length,
                        i = omitOffsets ? 8 : 0,
                        p;

                  for (; i < l; i += 2) {
                        p = state[i];
                        result.push(p, p in override ? override[p] : state[i + 1]);
                  }

                  result.t = state.t;
                  return result;
            },
            _winOffsets = {
                  left: 0,
                  top: 0
            },
            _parsePosition = function _parsePosition(value, trigger, scrollerSize, direction, scroll, marker, markerScroller, self, scrollerBounds, borderWidth, useFixedPosition, scrollerMax) {
                  _isFunction(value) && (value = value(self));

                  if (_isString(value) && value.substr(0, 3) === "max") {
                        value = scrollerMax + (value.charAt(4) === "=" ? _offsetToPx("0" + value.substr(3), scrollerSize) : 0);
                  }

                  if (!_isNumber(value)) {
                        _isFunction(trigger) && (trigger = trigger(self));

                        var element = _toArray(trigger)[0] || _body,
                              bounds = _getBounds(element) || {},
                              offsets = value.split(" "),
                              localOffset,
                              globalOffset,
                              display;

                        if ((!bounds || !bounds.left && !bounds.top) && _getComputedStyle(element).display === "none") {
                              display = element.style.display;
                              element.style.display = "block";
                              bounds = _getBounds(element);
                              display ? element.style.display = display : element.style.removeProperty("display");
                        }

                        localOffset = _offsetToPx(offsets[0], bounds[direction.d]);
                        globalOffset = _offsetToPx(offsets[1] || "0", scrollerSize);
                        value = bounds[direction.p] - scrollerBounds[direction.p] - borderWidth + localOffset + scroll - globalOffset;
                        markerScroller && _positionMarker(markerScroller, globalOffset, direction, scrollerSize - globalOffset < 20 || markerScroller._isStart && globalOffset > 20);
                        scrollerSize -= scrollerSize - globalOffset;
                  } else if (markerScroller) {
                        _positionMarker(markerScroller, scrollerSize, direction, true);
                  }

                  if (marker) {
                        var position = value + scrollerSize,
                              isStart = marker._isStart;
                        scrollerMax = "scroll" + direction.d2;

                        _positionMarker(marker, position, direction, isStart && position > 20 || !isStart && (useFixedPosition ? Math.max(_body[scrollerMax], _docEl[scrollerMax]) : marker.parentNode[scrollerMax]) <= position + 1);

                        if (useFixedPosition) {
                              scrollerBounds = _getBounds(markerScroller);
                              useFixedPosition && (marker.style[direction.op.p] = scrollerBounds[direction.op.p] - direction.op.m - marker._offset + _px);
                        }
                  }

                  return Math.round(value);
            },
            _prefixExp = /(?:webkit|moz|length|cssText|inset)/i,
            _reparent = function _reparent(element, parent, top, left) {
                  if (element.parentNode !== parent) {
                        var style = element.style,
                              p,
                              cs;

                        if (parent === _body) {
                              element._stOrig = style.cssText;
                              cs = _getComputedStyle(element);

                              for (p in cs) {
                                    if (!+p && !_prefixExp.test(p) && cs[p] && typeof style[p] === "string" && p !== "0") {
                                          style[p] = cs[p];
                                    }
                              }

                              style.top = top;
                              style.left = left;
                        } else {
                              style.cssText = element._stOrig;
                        }

                        gsap.core.getCache(element).uncache = 1;
                        parent.appendChild(element);
                  }
            },
            _getTweenCreator = function _getTweenCreator(scroller, direction) {
                  var getScroll = _getScrollFunc(scroller, direction),
                        prop = "_scroll" + direction.p2,
                        lastScroll1,
                        lastScroll2,
                        getTween = function getTween(scrollTo, vars, initialValue, change1, change2) {
                              var tween = getTween.tween,
                                    onComplete = vars.onComplete,
                                    modifiers = {};
                              tween && tween.kill();
                              lastScroll1 = Math.round(initialValue);
                              vars[prop] = scrollTo;
                              vars.modifiers = modifiers;

                              modifiers[prop] = function (value) {
                                    value = _round(getScroll());

                                    if (value !== lastScroll1 && value !== lastScroll2 && Math.abs(value - lastScroll1) > 2) {
                                          tween.kill();
                                          getTween.tween = 0;
                                    } else {
                                          value = initialValue + change1 * tween.ratio + change2 * tween.ratio * tween.ratio;
                                    }

                                    lastScroll2 = lastScroll1;
                                    return lastScroll1 = _round(value);
                              };

                              vars.onComplete = function () {
                                    getTween.tween = 0;
                                    onComplete && onComplete.call(tween);
                              };

                              tween = getTween.tween = gsap.to(scroller, vars);
                              return tween;
                        };

                  scroller[prop] = getScroll;
                  scroller.addEventListener("wheel", function () {
                        return getTween.tween && getTween.tween.kill() && (getTween.tween = 0);
                  }, {
                        passive: true
                  });
                  return getTween;
            };

      _horizontal.op = _vertical;
      var ScrollTrigger = function () {
            function ScrollTrigger(vars, animation) {
                  _coreInitted || ScrollTrigger.register(gsap) || console.warn("Please gsap.registerPlugin(ScrollTrigger)");
                  this.init(vars, animation);
            }

            var _proto = ScrollTrigger.prototype;

            _proto.init = function init(vars, animation) {
                  this.progress = this.start = 0;
                  this.vars && this.kill(1);

                  if (!_enabled) {
                        this.update = this.refresh = this.kill = _passThrough;
                        return;
                  }

                  vars = _setDefaults(_isString(vars) || _isNumber(vars) || vars.nodeType ? {
                        trigger: vars
                  } : vars, _defaults);

                  var direction = vars.horizontal ? _horizontal : _vertical,
                        _vars = vars,
                        onUpdate = _vars.onUpdate,
                        toggleClass = _vars.toggleClass,
                        id = _vars.id,
                        onToggle = _vars.onToggle,
                        onRefresh = _vars.onRefresh,
                        scrub = _vars.scrub,
                        trigger = _vars.trigger,
                        pin = _vars.pin,
                        pinSpacing = _vars.pinSpacing,
                        invalidateOnRefresh = _vars.invalidateOnRefresh,
                        anticipatePin = _vars.anticipatePin,
                        onScrubComplete = _vars.onScrubComplete,
                        onSnapComplete = _vars.onSnapComplete,
                        once = _vars.once,
                        snap = _vars.snap,
                        pinReparent = _vars.pinReparent,
                        isToggle = !scrub && scrub !== 0,
                        scroller = _toArray(vars.scroller || _win)[0],
                        scrollerCache = gsap.core.getCache(scroller),
                        isViewport = _isViewport(scroller),
                        useFixedPosition = "pinType" in vars ? vars.pinType === "fixed" : isViewport || _getProxyProp(scroller, "pinType") === "fixed",
                        callbacks = [vars.onEnter, vars.onLeave, vars.onEnterBack, vars.onLeaveBack],
                        toggleActions = isToggle && vars.toggleActions.split(" "),
                        markers = "markers" in vars ? vars.markers : _defaults.markers,
                        borderWidth = isViewport ? 0 : parseFloat(_getComputedStyle(scroller)["border" + direction.p2 + _Width]) || 0,
                        self = this,
                        onRefreshInit = vars.onRefreshInit && function () {
                              return vars.onRefreshInit(self);
                        },
                        getScrollerSize = _getSizeFunc(scroller, isViewport, direction),
                        getScrollerOffsets = _getOffsetsFunc(scroller, isViewport),
                        lastSnap = 0,
                        tweenTo,
                        pinCache,
                        snapFunc,
                        scroll1,
                        scroll2,
                        start,
                        end,
                        markerStart,
                        markerEnd,
                        markerStartTrigger,
                        markerEndTrigger,
                        markerVars,
                        change,
                        pinOriginalState,
                        pinActiveState,
                        pinState,
                        spacer,
                        offset,
                        pinGetter,
                        pinSetter,
                        pinStart,
                        pinChange,
                        spacingStart,
                        spacerState,
                        markerStartSetter,
                        markerEndSetter,
                        cs,
                        snap1,
                        snap2,
                        scrubTween,
                        scrubSmooth,
                        snapDurClamp,
                        snapDelayedCall,
                        prevProgress,
                        prevScroll,
                        prevAnimProgress;

                  self.media = _creatingMedia;
                  anticipatePin *= 45;
                  self.scroller = scroller;
                  self.scroll = _getScrollFunc(scroller, direction);
                  scroll1 = self.scroll();
                  self.vars = vars;
                  animation = animation || vars.animation;
                  "refreshPriority" in vars && (_sort = 1);
                  scrollerCache.tweenScroll = scrollerCache.tweenScroll || {
                        top: _getTweenCreator(scroller, _vertical),
                        left: _getTweenCreator(scroller, _horizontal)
                  };
                  self.tweenTo = tweenTo = scrollerCache.tweenScroll[direction.p];

                  if (animation) {
                        animation.vars.lazy = false;
                        animation._initted || animation.vars.immediateRender !== false && vars.immediateRender !== false && animation.render(0, true, true);
                        self.animation = animation.pause();
                        animation.scrollTrigger = self;
                        scrubSmooth = _isNumber(scrub) && scrub;
                        scrubSmooth && (scrubTween = gsap.to(animation, {
                              ease: "power3",
                              duration: scrubSmooth,
                              onComplete: function onComplete() {
                                    return onScrubComplete && onScrubComplete(self);
                              }
                        }));
                        snap1 = 0;
                        id || (id = animation.vars.id);
                  }

                  _triggers.push(self);

                  if (snap) {
                        if (!_isObject(snap) || snap.push) {
                              snap = {
                                    snapTo: snap
                              };
                        }

                        "scrollBehavior" in _body.style && gsap.set(isViewport ? [_body, _docEl] : scroller, {
                              scrollBehavior: "auto"
                        });
                        snapFunc = _isFunction(snap.snapTo) ? snap.snapTo : snap.snapTo === "labels" ? _getClosestLabel(animation) : snap.snapTo === "labelsDirectional" ? _getLabelAtDirection(animation) : gsap.utils.snap(snap.snapTo);
                        snapDurClamp = snap.duration || {
                              min: 0.1,
                              max: 2
                        };
                        snapDurClamp = _isObject(snapDurClamp) ? _clamp(snapDurClamp.min, snapDurClamp.max) : _clamp(snapDurClamp, snapDurClamp);
                        snapDelayedCall = gsap.delayedCall(snap.delay || scrubSmooth / 2 || 0.1, function () {
                              if (Math.abs(self.getVelocity()) < 10 && !_pointerIsDown && lastSnap !== self.scroll()) {
                                    var totalProgress = animation && !isToggle ? animation.totalProgress() : self.progress,
                                          velocity = (totalProgress - snap2) / (_getTime() - _time2) * 1000 || 0,
                                          change1 = gsap.utils.clamp(-self.progress, 1 - self.progress, _abs(velocity / 2) * velocity / 0.185),
                                          naturalEnd = self.progress + (snap.inertia === false ? 0 : change1),
                                          endValue = _clamp(0, 1, snapFunc(naturalEnd, self)),
                                          scroll = self.scroll(),
                                          endScroll = Math.round(start + endValue * change),
                                          _snap = snap,
                                          onStart = _snap.onStart,
                                          _onInterrupt = _snap.onInterrupt,
                                          _onComplete = _snap.onComplete,
                                          tween = tweenTo.tween;

                                    if (scroll <= end && scroll >= start && endScroll !== scroll) {
                                          if (tween && !tween._initted && tween.data <= Math.abs(endScroll - scroll)) {
                                                return;
                                          }

                                          if (snap.inertia === false) {
                                                change1 = endValue - self.progress;
                                          }

                                          tweenTo(endScroll, {
                                                duration: snapDurClamp(_abs(Math.max(_abs(naturalEnd - totalProgress), _abs(endValue - totalProgress)) * 0.185 / velocity / 0.05 || 0)),
                                                ease: snap.ease || "power3",
                                                data: Math.abs(endScroll - scroll),
                                                onInterrupt: function onInterrupt() {
                                                      return snapDelayedCall.restart(true) && _onInterrupt && _onInterrupt(self);
                                                },
                                                onComplete: function onComplete() {
                                                      lastSnap = self.scroll();
                                                      snap1 = snap2 = animation && !isToggle ? animation.totalProgress() : self.progress;
                                                      onSnapComplete && onSnapComplete(self);
                                                      _onComplete && _onComplete(self);
                                                }
                                          }, scroll, change1 * change, endScroll - scroll - change1 * change);
                                          onStart && onStart(self, tweenTo.tween);
                                    }
                              } else if (self.isActive) {
                                    snapDelayedCall.restart(true);
                              }
                        }).pause();
                  }

                  id && (_ids[id] = self);
                  trigger = self.trigger = _toArray(trigger || pin)[0];
                  pin = pin === true ? trigger : _toArray(pin)[0];
                  _isString(toggleClass) && (toggleClass = {
                        targets: trigger,
                        className: toggleClass
                  });

                  if (pin) {
                        pinSpacing === false || pinSpacing === _margin || (pinSpacing = !pinSpacing && _getComputedStyle(pin.parentNode).display === "flex" ? false : _padding);
                        self.pin = pin;
                        vars.force3D !== false && gsap.set(pin, {
                              force3D: true
                        });
                        pinCache = gsap.core.getCache(pin);

                        if (!pinCache.spacer) {
                              pinCache.spacer = spacer = _doc.createElement("div");
                              spacer.setAttribute("class", "pin-spacer" + (id ? " pin-spacer-" + id : ""));
                              pinCache.pinState = pinOriginalState = _getState(pin);
                        } else {
                              pinOriginalState = pinCache.pinState;
                        }

                        self.spacer = spacer = pinCache.spacer;
                        cs = _getComputedStyle(pin);
                        spacingStart = cs[pinSpacing + direction.os2];
                        pinGetter = gsap.getProperty(pin);
                        pinSetter = gsap.quickSetter(pin, direction.a, _px);

                        _swapPinIn(pin, spacer, cs);

                        pinState = _getState(pin);
                  }

                  if (markers) {
                        markerVars = _isObject(markers) ? _setDefaults(markers, _markerDefaults) : _markerDefaults;
                        markerStartTrigger = _createMarker("scroller-start", id, scroller, direction, markerVars, 0);
                        markerEndTrigger = _createMarker("scroller-end", id, scroller, direction, markerVars, 0, markerStartTrigger);
                        offset = markerStartTrigger["offset" + direction.op.d2];
                        markerStart = _createMarker("start", id, scroller, direction, markerVars, offset);
                        markerEnd = _createMarker("end", id, scroller, direction, markerVars, offset);

                        if (!useFixedPosition && !(_proxies.length && _getProxyProp(scroller, "fixedMarkers") === true)) {
                              _makePositionable(isViewport ? _body : scroller);

                              gsap.set([markerStartTrigger, markerEndTrigger], {
                                    force3D: true
                              });
                              markerStartSetter = gsap.quickSetter(markerStartTrigger, direction.a, _px);
                              markerEndSetter = gsap.quickSetter(markerEndTrigger, direction.a, _px);
                        }
                  }

                  self.revert = function (revert) {
                        var r = revert !== false || !self.enabled,
                              prevRefreshing = _refreshing;

                        if (r !== self.isReverted) {
                              if (r) {
                                    self.scroll.rec || (self.scroll.rec = self.scroll());
                                    prevScroll = Math.max(self.scroll(), self.scroll.rec || 0);
                                    prevProgress = self.progress;
                                    prevAnimProgress = animation && animation.progress();
                              }

                              markerStart && [markerStart, markerEnd, markerStartTrigger, markerEndTrigger].forEach(function (m) {
                                    return m.style.display = r ? "none" : "block";
                              });
                              r && (_refreshing = 1);
                              self.update(r);
                              _refreshing = prevRefreshing;
                              pin && (r ? _swapPinOut(pin, spacer, pinOriginalState) : (!pinReparent || !self.isActive) && _swapPinIn(pin, spacer, _getComputedStyle(pin), spacerState));
                              self.isReverted = r;
                        }
                  };

                  self.refresh = function (soft, force) {
                        if ((_refreshing || !self.enabled) && !force) {
                              return;
                        }

                        if (pin && soft && _lastScrollTime) {
                              _addListener(ScrollTrigger, "scrollEnd", _softRefresh);

                              return;
                        }

                        _refreshing = 1;
                        scrubTween && scrubTween.pause();
                        invalidateOnRefresh && animation && animation.progress(0).invalidate();
                        self.isReverted || self.revert();

                        var size = getScrollerSize(),
                              scrollerBounds = getScrollerOffsets(),
                              max = _maxScroll(scroller, direction),
                              offset = 0,
                              otherPinOffset = 0,
                              parsedEnd = vars.end,
                              parsedEndTrigger = vars.endTrigger || trigger,
                              parsedStart = vars.start || (vars.start === 0 || !trigger ? 0 : pin ? "0 0" : "0 100%"),
                              pinnedContainer = vars.pinnedContainer && _toArray(vars.pinnedContainer)[0],
                              triggerIndex = trigger && Math.max(0, _triggers.indexOf(self)) || 0,
                              i = triggerIndex,
                              cs,
                              bounds,
                              scroll,
                              isVertical,
                              override,
                              curTrigger,
                              curPin,
                              oppositeScroll,
                              initted,
                              revertedPins;

                        while (i--) {
                              curTrigger = _triggers[i];
                              curTrigger.end || curTrigger.refresh(0, 1) || (_refreshing = 1);
                              curPin = curTrigger.pin;

                              if (curPin && (curPin === trigger || curPin === pin) && !curTrigger.isReverted) {
                                    revertedPins || (revertedPins = []);
                                    revertedPins.unshift(curTrigger);
                                    curTrigger.revert();
                              }
                        }

                        start = _parsePosition(parsedStart, trigger, size, direction, self.scroll(), markerStart, markerStartTrigger, self, scrollerBounds, borderWidth, useFixedPosition, max) || (pin ? -0.001 : 0);
                        _isFunction(parsedEnd) && (parsedEnd = parsedEnd(self));

                        if (_isString(parsedEnd) && !parsedEnd.indexOf("+=")) {
                              if (~parsedEnd.indexOf(" ")) {
                                    parsedEnd = (_isString(parsedStart) ? parsedStart.split(" ")[0] : "") + parsedEnd;
                              } else {
                                    offset = _offsetToPx(parsedEnd.substr(2), size);
                                    parsedEnd = _isString(parsedStart) ? parsedStart : start + offset;
                                    parsedEndTrigger = trigger;
                              }
                        }

                        end = Math.max(start, _parsePosition(parsedEnd || (parsedEndTrigger ? "100% 0" : max), parsedEndTrigger, size, direction, self.scroll() + offset, markerEnd, markerEndTrigger, self, scrollerBounds, borderWidth, useFixedPosition, max)) || -0.001;
                        change = end - start || (start -= 0.01) && 0.001;
                        offset = 0;
                        i = triggerIndex;

                        while (i--) {
                              curTrigger = _triggers[i];
                              curPin = curTrigger.pin;

                              if (curPin && curTrigger.start - curTrigger._pinPush < start) {
                                    cs = curTrigger.end - curTrigger.start;
                                    (curPin === trigger || curPin === pinnedContainer) && (offset += cs);
                                    curPin === pin && (otherPinOffset += cs);
                              }
                        }

                        start += offset;
                        end += offset;
                        self._pinPush = otherPinOffset;

                        if (markerStart && offset) {
                              cs = {};
                              cs[direction.a] = "+=" + offset;
                              pinnedContainer && (cs[direction.p] = "-=" + self.scroll());
                              gsap.set([markerStart, markerEnd], cs);
                        }

                        if (pin) {
                              cs = _getComputedStyle(pin);
                              isVertical = direction === _vertical;
                              scroll = self.scroll();
                              pinStart = parseFloat(pinGetter(direction.a)) + otherPinOffset;
                              !max && end > 1 && ((isViewport ? _body : scroller).style["overflow-" + direction.a] = "scroll");

                              _swapPinIn(pin, spacer, cs);

                              pinState = _getState(pin);
                              bounds = _getBounds(pin, true);
                              oppositeScroll = useFixedPosition && _getScrollFunc(scroller, isVertical ? _horizontal : _vertical)();

                              if (pinSpacing) {
                                    spacerState = [pinSpacing + direction.os2, change + otherPinOffset + _px];
                                    spacerState.t = spacer;
                                    i = pinSpacing === _padding ? _getSize(pin, direction) + change + otherPinOffset : 0;
                                    i && spacerState.push(direction.d, i + _px);

                                    _setState(spacerState);

                                    useFixedPosition && self.scroll(prevScroll);
                              }

                              if (useFixedPosition) {
                                    override = {
                                          top: bounds.top + (isVertical ? scroll - start : oppositeScroll) + _px,
                                          left: bounds.left + (isVertical ? oppositeScroll : scroll - start) + _px,
                                          boxSizing: "border-box",
                                          position: "fixed"
                                    };
                                    override[_width] = override["max" + _Width] = Math.ceil(bounds.width) + _px;
                                    override[_height] = override["max" + _Height] = Math.ceil(bounds.height) + _px;
                                    override[_margin] = override[_margin + _Top] = override[_margin + _Right] = override[_margin + _Bottom] = override[_margin + _Left] = "0";
                                    override[_padding] = cs[_padding];
                                    override[_padding + _Top] = cs[_padding + _Top];
                                    override[_padding + _Right] = cs[_padding + _Right];
                                    override[_padding + _Bottom] = cs[_padding + _Bottom];
                                    override[_padding + _Left] = cs[_padding + _Left];
                                    pinActiveState = _copyState(pinOriginalState, override, pinReparent);
                              }

                              if (animation) {
                                    initted = animation._initted;

                                    _suppressOverwrites(1);

                                    animation.render(animation.duration(), true, true);
                                    pinChange = pinGetter(direction.a) - pinStart + change + otherPinOffset;
                                    change !== pinChange && pinActiveState.splice(pinActiveState.length - 2, 2);
                                    animation.render(0, true, true);
                                    initted || animation.invalidate();

                                    _suppressOverwrites(0);
                              } else {
                                    pinChange = change;
                              }
                        } else if (trigger && self.scroll()) {
                              bounds = trigger.parentNode;

                              while (bounds && bounds !== _body) {
                                    if (bounds._pinOffset) {
                                          start -= bounds._pinOffset;
                                          end -= bounds._pinOffset;
                                    }

                                    bounds = bounds.parentNode;
                              }
                        }

                        revertedPins && revertedPins.forEach(function (t) {
                              return t.revert(false);
                        });
                        self.start = start;
                        self.end = end;
                        scroll1 = scroll2 = self.scroll();
                        scroll1 < prevScroll && self.scroll(prevScroll);
                        self.revert(false);
                        _refreshing = 0;
                        animation && isToggle && animation._initted && animation.progress() !== prevAnimProgress && animation.progress(prevAnimProgress, true).render(animation.time(), true, true);

                        if (prevProgress !== self.progress) {
                              scrubTween && animation.totalProgress(prevProgress, true);
                              self.progress = prevProgress;
                              self.update();
                        }

                        pin && pinSpacing && (spacer._pinOffset = Math.round(self.progress * pinChange));
                        onRefresh && onRefresh(self);
                  };

                  self.getVelocity = function () {
                        return (self.scroll() - scroll2) / (_getTime() - _time2) * 1000 || 0;
                  };

                  self.update = function (reset, recordVelocity) {
                        var scroll = self.scroll(),
                              p = reset ? 0 : (scroll - start) / change,
                              clipped = p < 0 ? 0 : p > 1 ? 1 : p || 0,
                              prevProgress = self.progress,
                              isActive,
                              wasActive,
                              toggleState,
                              action,
                              stateChanged,
                              toggled;

                        if (recordVelocity) {
                              scroll2 = scroll1;
                              scroll1 = scroll;

                              if (snap) {
                                    snap2 = snap1;
                                    snap1 = animation && !isToggle ? animation.totalProgress() : clipped;
                              }
                        }

                        anticipatePin && !clipped && pin && !_refreshing && !_startup && _lastScrollTime && start < scroll + (scroll - scroll2) / (_getTime() - _time2) * anticipatePin && (clipped = 0.0001);

                        if (clipped !== prevProgress && self.enabled) {
                              isActive = self.isActive = !!clipped && clipped < 1;
                              wasActive = !!prevProgress && prevProgress < 1;
                              toggled = isActive !== wasActive;
                              stateChanged = toggled || !!clipped !== !!prevProgress;
                              self.direction = clipped > prevProgress ? 1 : -1;
                              self.progress = clipped;

                              if (!isToggle) {
                                    if (scrubTween && !_refreshing && !_startup) {
                                          scrubTween.vars.totalProgress = clipped;
                                          scrubTween.invalidate().restart();
                                    } else if (animation) {
                                          animation.totalProgress(clipped, !!_refreshing);
                                    }
                              }

                              if (pin) {
                                    reset && pinSpacing && (spacer.style[pinSpacing + direction.os2] = spacingStart);

                                    if (!useFixedPosition) {
                                          pinSetter(pinStart + pinChange * clipped);
                                    } else if (stateChanged) {
                                          action = !reset && clipped > prevProgress && end + 1 > scroll && scroll + 1 >= _maxScroll(scroller, direction);

                                          if (pinReparent) {
                                                if (!reset && (isActive || action)) {
                                                      var bounds = _getBounds(pin, true),
                                                            _offset = scroll - start;

                                                      _reparent(pin, _body, bounds.top + (direction === _vertical ? _offset : 0) + _px, bounds.left + (direction === _vertical ? 0 : _offset) + _px);
                                                } else {
                                                      _reparent(pin, spacer);
                                                }
                                          }

                                          _setState(isActive || action ? pinActiveState : pinState);

                                          pinChange !== change && clipped < 1 && isActive || pinSetter(pinStart + (clipped === 1 && !action ? pinChange : 0));
                                    }
                              }

                              snap && !tweenTo.tween && !_refreshing && !_startup && snapDelayedCall.restart(true);
                              toggleClass && (toggled || once && clipped && (clipped < 1 || !_limitCallbacks)) && _toArray(toggleClass.targets).forEach(function (el) {
                                    return el.classList[isActive || once ? "add" : "remove"](toggleClass.className);
                              });
                              onUpdate && !isToggle && !reset && onUpdate(self);

                              if (stateChanged && !_refreshing) {
                                    toggleState = clipped && !prevProgress ? 0 : clipped === 1 ? 1 : prevProgress === 1 ? 2 : 3;

                                    if (isToggle) {
                                          action = !toggled && toggleActions[toggleState + 1] !== "none" && toggleActions[toggleState + 1] || toggleActions[toggleState];

                                          if (animation && (action === "complete" || action === "reset" || action in animation)) {
                                                if (action === "complete") {
                                                      animation.pause().totalProgress(1);
                                                } else if (action === "reset") {
                                                      animation.restart(true).pause();
                                                } else if (action === "restart") {
                                                      animation.restart(true);
                                                } else {
                                                      animation[action]();
                                                }
                                          }

                                          onUpdate && onUpdate(self);
                                    }

                                    if (toggled || !_limitCallbacks) {
                                          onToggle && toggled && onToggle(self);
                                          callbacks[toggleState] && callbacks[toggleState](self);
                                          once && (clipped === 1 ? self.kill(false, 1) : callbacks[toggleState] = 0);

                                          if (!toggled) {
                                                toggleState = clipped === 1 ? 1 : 3;
                                                callbacks[toggleState] && callbacks[toggleState](self);
                                          }
                                    }
                              } else if (isToggle && onUpdate && !_refreshing) {
                                    onUpdate(self);
                              }
                        }

                        if (markerEndSetter) {
                              markerStartSetter(scroll + (markerStartTrigger._isFlipped ? 1 : 0));
                              markerEndSetter(scroll);
                        }
                  };

                  self.enable = function (reset, refresh) {
                        if (!self.enabled) {
                              self.enabled = true;

                              _addListener(scroller, "resize", _onResize);

                              _addListener(scroller, "scroll", _onScroll);

                              onRefreshInit && _addListener(ScrollTrigger, "refreshInit", onRefreshInit);

                              if (reset !== false) {
                                    self.progress = prevProgress = 0;
                                    scroll1 = scroll2 = lastSnap = self.scroll();
                              }

                              refresh !== false && self.refresh();
                        }
                  };

                  self.getTween = function (snap) {
                        return snap && tweenTo ? tweenTo.tween : scrubTween;
                  };

                  self.disable = function (reset, allowAnimation) {
                        if (self.enabled) {
                              reset !== false && self.revert();
                              self.enabled = self.isActive = false;
                              allowAnimation || scrubTween && scrubTween.pause();
                              prevScroll = 0;
                              pinCache && (pinCache.uncache = 1);
                              onRefreshInit && _removeListener(ScrollTrigger, "refreshInit", onRefreshInit);

                              if (snapDelayedCall) {
                                    snapDelayedCall.pause();
                                    tweenTo.tween && tweenTo.tween.kill() && (tweenTo.tween = 0);
                              }

                              if (!isViewport) {
                                    var i = _triggers.length;

                                    while (i--) {
                                          if (_triggers[i].scroller === scroller && _triggers[i] !== self) {
                                                return;
                                          }
                                    }

                                    _removeListener(scroller, "resize", _onResize);

                                    _removeListener(scroller, "scroll", _onScroll);
                              }
                        }
                  };

                  self.kill = function (revert, allowAnimation) {
                        self.disable(revert, allowAnimation);
                        id && delete _ids[id];

                        var i = _triggers.indexOf(self);

                        _triggers.splice(i, 1);

                        i === _i && _direction > 0 && _i--;
                        i = 0;

                        _triggers.forEach(function (t) {
                              return t.scroller === self.scroller && (i = 1);
                        });

                        i || (self.scroll.rec = 0);

                        if (animation) {
                              animation.scrollTrigger = null;
                              revert && animation.render(-1);
                              allowAnimation || animation.kill();
                        }

                        markerStart && [markerStart, markerEnd, markerStartTrigger, markerEndTrigger].forEach(function (m) {
                              return m.parentNode && m.parentNode.removeChild(m);
                        });

                        if (pin) {
                              pinCache && (pinCache.uncache = 1);
                              i = 0;

                              _triggers.forEach(function (t) {
                                    return t.pin === pin && i++;
                              });

                              i || (pinCache.spacer = 0);
                        }
                  };

                  self.enable(false, false);
                  !animation || !animation.add || change ? self.refresh() : gsap.delayedCall(0.01, function () {
                        return start || end || self.refresh();
                  }) && (change = 0.01) && (start = end = 0);
            };

            ScrollTrigger.register = function register(core) {
                  if (!_coreInitted) {
                        gsap = core || _getGSAP();

                        if (_windowExists() && window.document) {
                              _win = window;
                              _doc = document;
                              _docEl = _doc.documentElement;
                              _body = _doc.body;
                        }

                        if (gsap) {
                              _toArray = gsap.utils.toArray;
                              _clamp = gsap.utils.clamp;
                              _suppressOverwrites = gsap.core.suppressOverwrites || _passThrough;
                              gsap.core.globals("ScrollTrigger", ScrollTrigger);

                              if (_body) {
                                    _raf = _win.requestAnimationFrame || function (f) {
                                          return setTimeout(f, 16);
                                    };

                                    _addListener(_win, "wheel", _onScroll);

                                    _root = [_win, _doc, _docEl, _body];

                                    _addListener(_doc, "scroll", _onScroll);

                                    var bodyStyle = _body.style,
                                          border = bodyStyle.borderTop,
                                          bounds;
                                    bodyStyle.borderTop = "1px solid #000";
                                    bounds = _getBounds(_body);
                                    _vertical.m = Math.round(bounds.top + _vertical.sc()) || 0;
                                    _horizontal.m = Math.round(bounds.left + _horizontal.sc()) || 0;
                                    border ? bodyStyle.borderTop = border : bodyStyle.removeProperty("border-top");
                                    _syncInterval = setInterval(_sync, 200);
                                    gsap.delayedCall(0.5, function () {
                                          return _startup = 0;
                                    });

                                    _addListener(_doc, "touchcancel", _passThrough);

                                    _addListener(_body, "touchstart", _passThrough);

                                    _multiListener(_addListener, _doc, "pointerdown,touchstart,mousedown", function () {
                                          return _pointerIsDown = 1;
                                    });

                                    _multiListener(_addListener, _doc, "pointerup,touchend,mouseup", function () {
                                          return _pointerIsDown = 0;
                                    });

                                    _transformProp = gsap.utils.checkPrefix("transform");

                                    _stateProps.push(_transformProp);

                                    _coreInitted = _getTime();
                                    _resizeDelay = gsap.delayedCall(0.2, _refreshAll).pause();
                                    _autoRefresh = [_doc, "visibilitychange", function () {
                                          var w = _win.innerWidth,
                                                h = _win.innerHeight;

                                          if (_doc.hidden) {
                                                _prevWidth = w;
                                                _prevHeight = h;
                                          } else if (_prevWidth !== w || _prevHeight !== h) {
                                                _onResize();
                                          }
                                    }, _doc, "DOMContentLoaded", _refreshAll, _win, "load", function () {
                                          return _lastScrollTime || _refreshAll();
                                    }, _win, "resize", _onResize];

                                    _iterateAutoRefresh(_addListener);
                              }
                        }
                  }

                  return _coreInitted;
            };

            ScrollTrigger.defaults = function defaults(config) {
                  for (var p in config) {
                        _defaults[p] = config[p];
                  }
            };

            ScrollTrigger.kill = function kill() {
                  _enabled = 0;

                  _triggers.slice(0).forEach(function (trigger) {
                        return trigger.kill(1);
                  });
            };

            ScrollTrigger.config = function config(vars) {
                  "limitCallbacks" in vars && (_limitCallbacks = !!vars.limitCallbacks);
                  var ms = vars.syncInterval;
                  ms && clearInterval(_syncInterval) || (_syncInterval = ms) && setInterval(_sync, ms);

                  if ("autoRefreshEvents" in vars) {
                        _iterateAutoRefresh(_removeListener) || _iterateAutoRefresh(_addListener, vars.autoRefreshEvents || "none");
                        _ignoreResize = (vars.autoRefreshEvents + "").indexOf("resize") === -1;
                  }
            };

            ScrollTrigger.scrollerProxy = function scrollerProxy(target, vars) {
                  var t = _toArray(target)[0],
                        i = _scrollers.indexOf(t),
                        isViewport = _isViewport(t);

                  if (~i) {
                        _scrollers.splice(i, isViewport ? 6 : 2);
                  }

                  isViewport ? _proxies.unshift(_win, vars, _body, vars, _docEl, vars) : _proxies.unshift(t, vars);
            };

            ScrollTrigger.matchMedia = function matchMedia(vars) {
                  var mq, p, i, func, result;

                  for (p in vars) {
                        i = _media.indexOf(p);
                        func = vars[p];
                        _creatingMedia = p;

                        if (p === "all") {
                              func();
                        } else {
                              mq = _win.matchMedia(p);

                              if (mq) {
                                    mq.matches && (result = func());

                                    if (~i) {
                                          _media[i + 1] = _combineFunc(_media[i + 1], func);
                                          _media[i + 2] = _combineFunc(_media[i + 2], result);
                                    } else {
                                          i = _media.length;

                                          _media.push(p, func, result);

                                          mq.addListener ? mq.addListener(_onMediaChange) : mq.addEventListener("change", _onMediaChange);
                                    }

                                    _media[i + 3] = mq.matches;
                              }
                        }

                        _creatingMedia = 0;
                  }

                  return _media;
            };

            ScrollTrigger.clearMatchMedia = function clearMatchMedia(query) {
                  query || (_media.length = 0);
                  query = _media.indexOf(query);
                  query >= 0 && _media.splice(query, 4);
            };

            return ScrollTrigger;
      }();
      ScrollTrigger.version = "3.7.1";

      ScrollTrigger.saveStyles = function (targets) {
            return targets ? _toArray(targets).forEach(function (target) {
                  if (target && target.style) {
                        var i = _savedStyles.indexOf(target);

                        i >= 0 && _savedStyles.splice(i, 5);

                        _savedStyles.push(target, target.style.cssText, target.getBBox && target.getAttribute("transform"), gsap.core.getCache(target), _creatingMedia);
                  }
            }) : _savedStyles;
      };

      ScrollTrigger.revert = function (soft, media) {
            return _revertAll(!soft, media);
      };

      ScrollTrigger.create = function (vars, animation) {
            return new ScrollTrigger(vars, animation);
      };

      ScrollTrigger.refresh = function (safe) {
            return safe ? _onResize() : _refreshAll(true);
      };

      ScrollTrigger.update = _updateAll;

      ScrollTrigger.maxScroll = function (element, horizontal) {
            return _maxScroll(element, horizontal ? _horizontal : _vertical);
      };

      ScrollTrigger.getScrollFunc = function (element, horizontal) {
            return _getScrollFunc(_toArray(element)[0], horizontal ? _horizontal : _vertical);
      };

      ScrollTrigger.getById = function (id) {
            return _ids[id];
      };

      ScrollTrigger.getAll = function () {
            return _triggers.slice(0);
      };

      ScrollTrigger.isScrolling = function () {
            return !!_lastScrollTime;
      };

      ScrollTrigger.addEventListener = function (type, callback) {
            var a = _listeners[type] || (_listeners[type] = []);
            ~a.indexOf(callback) || a.push(callback);
      };

      ScrollTrigger.removeEventListener = function (type, callback) {
            var a = _listeners[type],
                  i = a && a.indexOf(callback);
            i >= 0 && a.splice(i, 1);
      };

      ScrollTrigger.batch = function (targets, vars) {
            var result = [],
                  varsCopy = {},
                  interval = vars.interval || 0.016,
                  batchMax = vars.batchMax || 1e9,
                  proxyCallback = function proxyCallback(type, callback) {
                        var elements = [],
                              triggers = [],
                              delay = gsap.delayedCall(interval, function () {
                                    callback(elements, triggers);
                                    elements = [];
                                    triggers = [];
                              }).pause();
                        return function (self) {
                              elements.length || delay.restart(true);
                              elements.push(self.trigger);
                              triggers.push(self);
                              batchMax <= elements.length && delay.progress(1);
                        };
                  },
                  p;

            for (p in vars) {
                  varsCopy[p] = p.substr(0, 2) === "on" && _isFunction(vars[p]) && p !== "onRefreshInit" ? proxyCallback(p, vars[p]) : vars[p];
            }

            if (_isFunction(batchMax)) {
                  batchMax = batchMax();

                  _addListener(ScrollTrigger, "refresh", function () {
                        return batchMax = vars.batchMax();
                  });
            }

            _toArray(targets).forEach(function (target) {
                  var config = {};

                  for (p in varsCopy) {
                        config[p] = varsCopy[p];
                  }

                  config.trigger = target;
                  result.push(ScrollTrigger.create(config));
            });

            return result;
      };

      ScrollTrigger.sort = function (func) {
            return _triggers.sort(func || function (a, b) {
                  return (a.vars.refreshPriority || 0) * -1e6 + a.start - (b.start + (b.vars.refreshPriority || 0) * -1e6);
            });
      };

      _getGSAP() && gsap.registerPlugin(ScrollTrigger);

      exports.ScrollTrigger = ScrollTrigger;
      exports.default = ScrollTrigger;

      Object.defineProperty(exports, '__esModule', { value: true });

})));