(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define("Barba", [], factory);
	else if(typeof exports === 'object')
		exports["Barba"] = factory();
	else
		root["Barba"] = factory();
})(this, function() {
	return /******/ (function(modules) { // webpackBootstrap
		/******/ 	// The module cache
		/******/ 	var installedModules = {};
		/******/
		/******/ 	// The require function
		/******/ 	function __webpack_require__(moduleId) {
			/******/
			/******/ 		// Check if module is in cache
			/******/ 		if(installedModules[moduleId])
			/******/ 			return installedModules[moduleId].exports;
			/******/
			/******/ 		// Create a new module (and put it into the cache)
			/******/ 		var module = installedModules[moduleId] = {
				/******/ 			exports: {},
				/******/ 			id: moduleId,
				/******/ 			loaded: false
				/******/ 		};
			/******/
			/******/ 		// Execute the module function
			/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
			/******/
			/******/ 		// Flag the module as loaded
			/******/ 		module.loaded = true;
			/******/
			/******/ 		// Return the exports of the module
			/******/ 		return module.exports;
			/******/ 	}
		/******/
		/******/
		/******/ 	// expose the modules object (__webpack_modules__)
		/******/ 	__webpack_require__.m = modules;
		/******/
		/******/ 	// expose the module cache
		/******/ 	__webpack_require__.c = installedModules;
		/******/
		/******/ 	// __webpack_public_path__
		/******/ 	__webpack_require__.p = "http://localhost:8080/dist";
		/******/
		/******/ 	// Load entry module and return exports
		/******/ 	return __webpack_require__(0);
		/******/ })
	/************************************************************************/
	/******/ ([
		/* 0 */
		/***/ function(module, exports, __webpack_require__) {

			//Promise polyfill https://github.com/taylorhakes/promise-polyfill

			if (typeof Promise !== 'function') {
				window.Promise = __webpack_require__(1);
			}

			var Barba = {
				version: '1.0.0',
				BaseTransition: __webpack_require__(4),
				BaseView: __webpack_require__(6),
				BaseCache: __webpack_require__(8),
				Dispatcher: __webpack_require__(7),
				HistoryManager: __webpack_require__(9),
				Pjax: __webpack_require__(10),
				Prefetch: __webpack_require__(13),
				Utils: __webpack_require__(5)
			};

			module.exports = Barba;


			/***/ },
		/* 1 */
		/***/ function(module, exports, __webpack_require__) {

			/* WEBPACK VAR INJECTION */(function(setImmediate) {(function (root) {

				// Store setTimeout reference so promise-polyfill will be unaffected by
				// other code modifying setTimeout (like sinon.useFakeTimers())
				var setTimeoutFunc = setTimeout;

				function noop() {
				}

				// Use polyfill for setImmediate for performance gains
				var asap = (typeof setImmediate === 'function' && setImmediate) ||
				           function (fn) {
					           setTimeoutFunc(fn, 0);
				           };

				var onUnhandledRejection = function onUnhandledRejection(err) {
					if (typeof console !== 'undefined' && console) {
						console.warn('Possible Unhandled Promise Rejection:', err); // eslint-disable-line no-console
					}
				};

				// Polyfill for Function.prototype.bind
				function bind(fn, thisArg) {
					return function () {
						fn.apply(thisArg, arguments);
					};
				}

				function Promise(fn) {
					if (typeof this !== 'object') throw new TypeError('Promises must be constructed via new');
					if (typeof fn !== 'function') throw new TypeError('not a function');
					this._state = 0;
					this._handled = false;
					this._value = undefined;
					this._deferreds = [];

					doResolve(fn, this);
				}

				function handle(self, deferred) {
					while (self._state === 3) {
						self = self._value;
					}
					if (self._state === 0) {
						self._deferreds.push(deferred);
						return;
					}
					self._handled = true;
					asap(function () {
						var cb = self._state === 1 ? deferred.onFulfilled : deferred.onRejected;
						if (cb === null) {
							(self._state === 1 ? resolve : reject)(deferred.promise, self._value);
							return;
						}
						var ret;
						try {
							ret = cb(self._value);
						} catch (e) {
							reject(deferred.promise, e);
							return;
						}
						resolve(deferred.promise, ret);
					});
				}

				function resolve(self, newValue) {
					try {
						// Promise Resolution Procedure: https://github.com/promises-aplus/promises-spec#the-promise-resolution-procedure
						if (newValue === self) throw new TypeError('A promise cannot be resolved with itself.');
						if (newValue && (typeof newValue === 'object' || typeof newValue === 'function')) {
							var then = newValue.then;
							if (newValue instanceof Promise) {
								self._state = 3;
								self._value = newValue;
								finale(self);
								return;
							} else if (typeof then === 'function') {
								doResolve(bind(then, newValue), self);
								return;
							}
						}
						self._state = 1;
						self._value = newValue;
						finale(self);
					} catch (e) {
						reject(self, e);
					}
				}

				function reject(self, newValue) {
					self._state = 2;
					self._value = newValue;
					finale(self);
				}

				function finale(self) {
					if (self._state === 2 && self._deferreds.length === 0) {
						asap(function() {
							if (!self._handled) {
								onUnhandledRejection(self._value);
							}
						});
					}

					for (var i = 0, len = self._deferreds.length; i < len; i++) {
						handle(self, self._deferreds[i]);
					}
					self._deferreds = null;
				}

				function Handler(onFulfilled, onRejected, promise) {
					this.onFulfilled = typeof onFulfilled === 'function' ? onFulfilled : null;
					this.onRejected = typeof onRejected === 'function' ? onRejected : null;
					this.promise = promise;
				}

				/**
				 * Take a potentially misbehaving resolver function and make sure
				 * onFulfilled and onRejected are only called once.
				 *
				 * Makes no guarantees about asynchrony.
				 */
				function doResolve(fn, self) {
					var done = false;
					try {
						fn(function (value) {
							if (done) return;
							done = true;
							resolve(self, value);
						}, function (reason) {
							if (done) return;
							done = true;
							reject(self, reason);
						});
					} catch (ex) {
						if (done) return;
						done = true;
						reject(self, ex);
					}
				}

				Promise.prototype['catch'] = function (onRejected) {
					return this.then(null, onRejected);
				};

				Promise.prototype.then = function (onFulfilled, onRejected) {
					var prom = new (this.constructor)(noop);

					handle(this, new Handler(onFulfilled, onRejected, prom));
					return prom;
				};

				Promise.all = function (arr) {
					var args = Array.prototype.slice.call(arr);

					return new Promise(function (resolve, reject) {
						if (args.length === 0) return resolve([]);
						var remaining = args.length;

						function res(i, val) {
							try {
								if (val && (typeof val === 'object' || typeof val === 'function')) {
									var then = val.then;
									if (typeof then === 'function') {
										then.call(val, function (val) {
											res(i, val);
										}, reject);
										return;
									}
								}
								args[i] = val;
								if (--remaining === 0) {
									resolve(args);
								}
							} catch (ex) {
								reject(ex);
							}
						}

						for (var i = 0; i < args.length; i++) {
							res(i, args[i]);
						}
					});
				};

				Promise.resolve = function (value) {
					if (value && typeof value === 'object' && value.constructor === Promise) {
						return value;
					}

					return new Promise(function (resolve) {
						resolve(value);
					});
				};

				Promise.reject = function (value) {
					return new Promise(function (resolve, reject) {
						reject(value);
					});
				};

				Promise.race = function (values) {
					return new Promise(function (resolve, reject) {
						for (var i = 0, len = values.length; i < len; i++) {
							values[i].then(resolve, reject);
						}
					});
				};

				/**
				 * Set the immediate function to execute callbacks
				 * @param fn {function} Function to execute
				 * @private
				 */
				Promise._setImmediateFn = function _setImmediateFn(fn) {
					asap = fn;
				};

				Promise._setUnhandledRejectionFn = function _setUnhandledRejectionFn(fn) {
					onUnhandledRejection = fn;
				};

				if (typeof module !== 'undefined' && module.exports) {
					module.exports = Promise;
				} else if (!root.Promise) {
					root.Promise = Promise;
				}

			})(this);

				/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2).setImmediate))

			/***/ },
		/* 2 */
		/***/ function(module, exports, __webpack_require__) {

			/* WEBPACK VAR INJECTION */(function(setImmediate, clearImmediate) {var nextTick = __webpack_require__(3).nextTick;
				var apply = Function.prototype.apply;
				var slice = Array.prototype.slice;
				var immediateIds = {};
				var nextImmediateId = 0;

				// DOM APIs, for completeness

				exports.setTimeout = function() {
					return new Timeout(apply.call(setTimeout, window, arguments), clearTimeout);
				};
				exports.setInterval = function() {
					return new Timeout(apply.call(setInterval, window, arguments), clearInterval);
				};
				exports.clearTimeout =
					exports.clearInterval = function(timeout) { timeout.close(); };

				function Timeout(id, clearFn) {
					this._id = id;
					this._clearFn = clearFn;
				}
				Timeout.prototype.unref = Timeout.prototype.ref = function() {};
				Timeout.prototype.close = function() {
					this._clearFn.call(window, this._id);
				};

				// Does not start the time, just sets up the members needed.
				exports.enroll = function(item, msecs) {
					clearTimeout(item._idleTimeoutId);
					item._idleTimeout = msecs;
				};

				exports.unenroll = function(item) {
					clearTimeout(item._idleTimeoutId);
					item._idleTimeout = -1;
				};

				exports._unrefActive = exports.active = function(item) {
					clearTimeout(item._idleTimeoutId);

					var msecs = item._idleTimeout;
					if (msecs >= 0) {
						item._idleTimeoutId = setTimeout(function onTimeout() {
							if (item._onTimeout)
								item._onTimeout();
						}, msecs);
					}
				};

				// That's not how node.js implements it but the exposed api is the same.
				exports.setImmediate = typeof setImmediate === "function" ? setImmediate : function(fn) {
					var id = nextImmediateId++;
					var args = arguments.length < 2 ? false : slice.call(arguments, 1);

					immediateIds[id] = true;

					nextTick(function onNextTick() {
						if (immediateIds[id]) {
							// fn.call() is faster so we optimize for the common use-case
							// @see http://jsperf.com/call-apply-segu
							if (args) {
								fn.apply(null, args);
							} else {
								fn.call(null);
							}
							// Prevent ids from leaking
							exports.clearImmediate(id);
						}
					});

					return id;
				};

				exports.clearImmediate = typeof clearImmediate === "function" ? clearImmediate : function(id) {
					delete immediateIds[id];
				};
				/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(2).setImmediate, __webpack_require__(2).clearImmediate))

			/***/ },
		/* 3 */
		/***/ function(module, exports) {

			// shim for using process in browser

			var process = module.exports = {};

			// cached from whatever global is present so that test runners that stub it
			// don't break things.  But we need to wrap it in a try catch in case it is
			// wrapped in strict mode code which doesn't define any globals.  It's inside a
			// function because try/catches deoptimize in certain engines.

			var cachedSetTimeout;
			var cachedClearTimeout;

			(function () {
				try {
					cachedSetTimeout = setTimeout;
				} catch (e) {
					cachedSetTimeout = function () {
						throw new Error('setTimeout is not defined');
					}
				}
				try {
					cachedClearTimeout = clearTimeout;
				} catch (e) {
					cachedClearTimeout = function () {
						throw new Error('clearTimeout is not defined');
					}
				}
			} ())
			var queue = [];
			var draining = false;
			var currentQueue;
			var queueIndex = -1;

			function cleanUpNextTick() {
				if (!draining || !currentQueue) {
					return;
				}
				draining = false;
				if (currentQueue.length) {
					queue = currentQueue.concat(queue);
				} else {
					queueIndex = -1;
				}
				if (queue.length) {
					drainQueue();
				}
			}

			function drainQueue() {
				if (draining) {
					return;
				}
				var timeout = cachedSetTimeout(cleanUpNextTick);
				draining = true;

				var len = queue.length;
				while(len) {
					currentQueue = queue;
					queue = [];
					while (++queueIndex < len) {
						if (currentQueue) {
							currentQueue[queueIndex].run();
						}
					}
					queueIndex = -1;
					len = queue.length;
				}
				currentQueue = null;
				draining = false;
				cachedClearTimeout(timeout);
			}

			process.nextTick = function (fun) {
				var args = new Array(arguments.length - 1);
				if (arguments.length > 1) {
					for (var i = 1; i < arguments.length; i++) {
						args[i - 1] = arguments[i];
					}
				}
				queue.push(new Item(fun, args));
				if (queue.length === 1 && !draining) {
					cachedSetTimeout(drainQueue, 0);
				}
			};

			// v8 likes predictible objects
			function Item(fun, array) {
				this.fun = fun;
				this.array = array;
			}
			Item.prototype.run = function () {
				this.fun.apply(null, this.array);
			};
			process.title = 'browser';
			process.browser = true;
			process.env = {};
			process.argv = [];
			process.version = ''; // empty string to avoid regexp issues
			process.versions = {};

			function noop() {}

			process.on = noop;
			process.addListener = noop;
			process.once = noop;
			process.off = noop;
			process.removeListener = noop;
			process.removeAllListeners = noop;
			process.emit = noop;

			process.binding = function (name) {
				throw new Error('process.binding is not supported');
			};

			process.cwd = function () { return '/' };
			process.chdir = function (dir) {
				throw new Error('process.chdir is not supported');
			};
			process.umask = function() { return 0; };


			/***/ },
		/* 4 */
		/***/ function(module, exports, __webpack_require__) {

			var Utils = __webpack_require__(5);

			/**
			 * BaseTransition to extend
			 *
			 * @namespace Barba.BaseTransition
			 * @type {Object}
			 */
			var BaseTransition = {
				/**
				 * @memberOf Barba.BaseTransition
				 * @type {HTMLElement}
				 */
				oldContainer: undefined,

				/**
				 * @memberOf Barba.BaseTransition
				 * @type {HTMLElement}
				 */
				newContainer: undefined,

				/**
				 * @memberOf Barba.BaseTransition
				 * @type {Promise}
				 */
				newContainerLoading: undefined,

				/**
				 * Helper to extend the object
				 *
				 * @memberOf Barba.BaseTransition
				 * @param  {Object} newObject
				 * @return {Object} newInheritObject
				 */
				extend: function(obj){
					return Utils.extend(this, obj);
				},

				/**
				 * This function is called from Pjax module to initialize
				 * the transition.
				 *
				 * @memberOf Barba.BaseTransition
				 * @private
				 * @param  {HTMLElement} oldContainer
				 * @param  {Promise} newContainer
				 * @return {Promise}
				 */
				init: function(oldContainer, newContainer) {
					var _this = this;

					this.oldContainer = oldContainer;
					this._newContainerPromise = newContainer;

					this.deferred = Utils.deferred();
					this.newContainerReady = Utils.deferred();
					this.newContainerLoading = this.newContainerReady.promise;

					this.start();

					this._newContainerPromise.then(function(newContainer) {
						_this.newContainer = newContainer;
						_this.newContainerReady.resolve();
					});

					return this.deferred.promise;
				},

				/**
				 * This function needs to be called as soon the Transition is finished
				 *
				 * @memberOf Barba.BaseTransition
				 */
				done: function() {
					this.oldContainer.parentNode.removeChild(this.oldContainer);
					this.newContainer.style.visibility = 'visible';
					this.deferred.resolve();
				},

				/**
				 * Constructor for your Transition
				 *
				 * @memberOf Barba.BaseTransition
				 * @abstract
				 */
				start: function() {},
			};

			module.exports = BaseTransition;


			/***/ },
		/* 5 */
		/***/ function(module, exports) {

			/**
			 * Just an object with some helpful functions
			 *
			 * @type {Object}
			 * @namespace Barba.Utils
			 */
			var Utils = {
				/**
				 * Return the current url
				 *
				 * @memberOf Barba.Utils
				 * @return {String} currentUrl
				 */
				getCurrentUrl: function() {
					return window.location.protocol + '//' +
					       window.location.host +
					       window.location.pathname +
					       window.location.search;
				},

				/**
				 * Given an url, return it without the hash
				 *
				 * @memberOf Barba.Utils
				 * @private
				 * @param  {String} url
				 * @return {String} newCleanUrl
				 */
				cleanLink: function(url) {
					return url.replace(/#.*/, '');
				},

				/**
				 * Time in millisecond after the xhr request goes in timeout
				 *
				 * @memberOf Barba.Utils
				 * @type {Number}
				 * @default
				 */
				xhrTimeout: 5000,

				/**
				 * Start an XMLHttpRequest() and return a Promise
				 *
				 * @memberOf Barba.Utils
				 * @param  {String} url
				 * @return {Promise}
				 */
				xhr: function(url) {
					var deferred = this.deferred();
					var req = new XMLHttpRequest();

					req.onreadystatechange = function() {
						if (req.readyState === 4) {
							if (req.status === 200) {
								return deferred.resolve(req.responseText);
							} else {
								return deferred.reject(new Error('xhr: HTTP code is not 200'));
							}
						}
					};

					req.ontimeout = function() {
						return deferred.reject(new Error('xhr: Timeout exceeded'));
					};

					req.open('GET', url);
					req.timeout = this.xhrTimeout;
					req.setRequestHeader('x-barba', 'yes');
					req.send();

					return deferred.promise;
				},

				/**
				 * Get obj and props and return a new object with the property merged
				 *
				 * @memberOf Barba.Utils
				 * @param  {object} obj
				 * @param  {object} props
				 * @return {object}
				 */
				extend: function(obj, props) {
					var newObj = Object.create(obj);

					for(var prop in props) {
						if(props.hasOwnProperty(prop)) {
							newObj[prop] = props[prop];
						}
					}

					return newObj;
				},

				/**
				 * Return a new "Deferred" object
				 * https://developer.mozilla.org/en-US/docs/Mozilla/JavaScript_code_modules/Promise.jsm/Deferred
				 *
				 * @memberOf Barba.Utils
				 * @return {Deferred}
				 */
				deferred: function() {
					return new function() {
						this.resolve = null;
						this.reject = null;

						this.promise = new Promise(function(resolve, reject) {
							this.resolve = resolve;
							this.reject = reject;
						}.bind(this));
					};
				},

				/**
				 * Return the port number normalized, eventually you can pass a string to be normalized.
				 *
				 * @memberOf Barba.Utils
				 * @private
				 * @param  {String} p
				 * @return {Int} port
				 */
				getPort: function(p) {
					var port = typeof p !== 'undefined' ? p : window.location.port;
					var protocol = window.location.protocol;

					if (port != '')
						return parseInt(port);

					if (protocol === 'http:')
						return 80;

					if (protocol === 'https:')
						return 443;
				}
			};

			module.exports = Utils;


			/***/ },
		/* 6 */
		/***/ function(module, exports, __webpack_require__) {

			var Dispatcher = __webpack_require__(7);
			var Utils = __webpack_require__(5);

			/**
			 * BaseView to be extended
			 *
			 * @namespace Barba.BaseView
			 * @type {Object}
			 */
			var BaseView  = {
				/**
				 * Namespace of the view.
				 * (need to be associated with the data-namespace of the container)
				 *
				 * @memberOf Barba.BaseView
				 * @type {String}
				 */
				namespace: null,

				/**
				 * Helper to extend the object
				 *
				 * @memberOf Barba.BaseView
				 * @param  {Object} newObject
				 * @return {Object} newInheritObject
				 */
				extend: function(obj){
					return Utils.extend(this, obj);
				},

				/**
				 * Init the view.
				 * P.S. Is suggested to init the view before starting Barba.Pjax.start(),
				 * in this way .onEnter() and .onEnterCompleted() will be fired for the current
				 * container when the page is loaded.
				 *
				 * @memberOf Barba.BaseView
				 */
				init: function() {
					var _this = this;

					Dispatcher.on('initStateChange',
						function(newStatus, oldStatus) {
							if (oldStatus && oldStatus.namespace === _this.namespace)
								_this.onLeave();
						}
					);

					Dispatcher.on('newPageReady',
						function(newStatus, oldStatus, container) {
							_this.container = container;

							if (newStatus.namespace === _this.namespace)
								_this.onEnter();
						}
					);

					Dispatcher.on('transitionCompleted',
						function(newStatus, oldStatus) {
							if (newStatus.namespace === _this.namespace)
								_this.onEnterCompleted();

							if (oldStatus && oldStatus.namespace === _this.namespace)
								_this.onLeaveCompleted();
						}
					);
				},

				/**
				 * This function will be fired when the container
				 * is ready and attached to the DOM.
				 *
				 * @memberOf Barba.BaseView
				 * @abstract
				 */
				onEnter: function() {},

				/**
				 * This function will be fired when the transition
				 * to this container has just finished.
				 *
				 * @memberOf Barba.BaseView
				 * @abstract
				 */
				onEnterCompleted: function() {},

				/**
				 * This function will be fired when the transition
				 * to a new container has just started.
				 *
				 * @memberOf Barba.BaseView
				 * @abstract
				 */
				onLeave: function() {},

				/**
				 * This function will be fired when the container
				 * has just been removed from the DOM.
				 *
				 * @memberOf Barba.BaseView
				 * @abstract
				 */
				onLeaveCompleted: function() {}
			}

			module.exports = BaseView;


			/***/ },
		/* 7 */
		/***/ function(module, exports) {

			/**
			 * Little Dispatcher inspired by MicroEvent.js
			 *
			 * @namespace Barba.Dispatcher
			 * @type {Object}
			 */
			var Dispatcher = {
				/**
				 * Object that keeps all the events
				 *
				 * @memberOf Barba.Dispatcher
				 * @readOnly
				 * @type {Object}
				 */
				events: {},

				/**
				 * Bind a callback to an event
				 *
				 * @memberOf Barba.Dispatcher
				 * @param  {String} eventName
				 * @param  {Function} function
				 */
				on: function(e, f) {
					this.events[e] = this.events[e] || [];
					this.events[e].push(f);
				},

				/**
				 * Unbind event
				 *
				 * @memberOf Barba.Dispatcher
				 * @param  {String} eventName
				 * @param  {Function} function
				 */
				off: function(e, f) {
					if(e in this.events === false)
						return;

					this.events[e].splice(this.events[e].indexOf(f), 1);
				},

				/**
				 * Fire the event running all the event associated to it
				 *
				 * @memberOf Barba.Dispatcher
				 * @param  {String} eventName
				 * @param  {...*} args
				 */
				trigger: function(e) {//e, ...args
					if (e in this.events === false)
						return;

					for(var i = 0; i < this.events[e].length; i++){
						this.events[e][i].apply(this, Array.prototype.slice.call(arguments, 1));
					}
				}
			};

			module.exports = Dispatcher;


			/***/ },
		/* 8 */
		/***/ function(module, exports, __webpack_require__) {

			var Utils = __webpack_require__(5);

			/**
			 * BaseCache it's a simple static cache
			 *
			 * @namespace Barba.BaseCache
			 * @type {Object}
			 */
			var BaseCache = {
				/**
				 * The Object that keeps all the key value information
				 *
				 * @memberOf Barba.BaseCache
				 * @type {Object}
				 */
				data: {},

				/**
				 * Helper to extend this object
				 *
				 * @memberOf Barba.BaseCache
				 * @private
				 * @param  {Object} newObject
				 * @return {Object} newInheritObject
				 */
				extend: function(obj) {
					return Utils.extend(this, obj);
				},

				/**
				 * Set a key and value data, mainly Barba is going to save promises
				 *
				 * @memberOf Barba.BaseCache
				 * @param {String} key
				 * @param {*} value
				 */
				set: function(key, val) {
					this.data[key] = val;
				},

				/**
				 * Retrieve the data using the key
				 *
				 * @memberOf Barba.BaseCache
				 * @param  {String} key
				 * @return {*}
				 */
				get: function(key) {
					return this.data[key];
				},

				/**
				 * Flush the cache
				 *
				 * @memberOf Barba.BaseCache
				 */
				reset: function() {
					this.data = {};
				}
			};

			module.exports = BaseCache;


			/***/ },
		/* 9 */
		/***/ function(module, exports) {

			/**
			 * HistoryManager helps to keep track of the navigation
			 *
			 * @namespace Barba.HistoryManager
			 * @type {Object}
			 */
			var HistoryManager = {
				/**
				 * Keep track of the status in historic order
				 *
				 * @memberOf Barba.HistoryManager
				 * @readOnly
				 * @type {Array}
				 */
				history: [],

				/**
				 * Add a new set of url and namespace
				 *
				 * @memberOf Barba.HistoryManager
				 * @param {String} url
				 * @param {String} namespace
				 * @private
				 */
				add: function(url, namespace) {
					if (!namespace)
						namespace = undefined;

					this.history.push({
						url: url,
						namespace: namespace
					});
				},

				/**
				 * Return information about the current status
				 *
				 * @memberOf Barba.HistoryManager
				 * @return {Object}
				 */
				currentStatus: function() {
					return this.history[this.history.length - 1];
				},

				/**
				 * Return information about the previous status
				 *
				 * @memberOf Barba.HistoryManager
				 * @return {Object}
				 */
				prevStatus: function() {
					var history = this.history;

					if (history.length < 2)
						return null;

					return history[history.length - 2];
				}
			};

			module.exports = HistoryManager;


			/***/ },
		/* 10 */
		/***/ function(module, exports, __webpack_require__) {

			var Utils = __webpack_require__(5);
			var Dispatcher = __webpack_require__(7);
			var HideShowTransition = __webpack_require__(11);
			var BaseCache = __webpack_require__(8);

			var HistoryManager = __webpack_require__(9);
			var Dom = __webpack_require__(12);

			/**
			 * Pjax is a static object with main function
			 *
			 * @namespace Barba.Pjax
			 * @borrows Dom as Dom
			 * @type {Object}
			 */
			var Pjax = {
				Dom: Dom,
				History: HistoryManager,
				Cache: BaseCache,

				/**
				 * Indicate wether or not use the cache
				 *
				 * @memberOf Barba.Pjax
				 * @type {Boolean}
				 * @default
				 */
				cacheEnabled: true,

				/**
				 * Indicate if there is an animation in progress
				 *
				 * @memberOf Barba.Pjax
				 * @readOnly
				 * @type {Boolean}
				 */
				transitionProgress: false,

				/**
				 * Class name used to ignore links
				 *
				 * @memberOf Barba.Pjax
				 * @type {String}
				 * @default
				 */
				ignoreClassLink: 'no-barba',

				/**
				 * Function to be called to start Pjax
				 *
				 * @memberOf Barba.Pjax
				 */
				start: function() {
					this.init();
				},

				/**
				 * Init the events
				 *
				 * @memberOf Barba.Pjax
				 * @private
				 */
				init: function() {
					var container = this.Dom.getContainer();
					var wrapper = this.Dom.getWrapper();

					wrapper.setAttribute('aria-live', 'polite');

					this.History.add(
						this.getCurrentUrl(),
						this.Dom.getNamespace(container)
					);

					//Fire for the current view.
					Dispatcher.trigger('initStateChange', this.History.currentStatus());
					Dispatcher.trigger('newPageReady',
						this.History.currentStatus(),
						{},
						container,
						this.Dom.currentHTML
					);
					Dispatcher.trigger('transitionCompleted', this.History.currentStatus());

					this.bindEvents();
				},

				/**
				 * Attach the eventlisteners
				 *
				 * @memberOf Barba.Pjax
				 * @private
				 */
				bindEvents: function() {
					document.addEventListener('click',
						this.onLinkClick.bind(this)
					);

					window.addEventListener('popstate',
						this.onStateChange.bind(this)
					);
				},

				/**
				 * Return the currentURL cleaned
				 *
				 * @memberOf Barba.Pjax
				 * @return {String} currentUrl
				 */
				getCurrentUrl: function() {
					return Utils.cleanLink(
						Utils.getCurrentUrl()
					);
				},

				/**
				 * Change the URL with pushstate and trigger the state change
				 *
				 * @memberOf Barba.Pjax
				 * @param {String} newUrl
				 */
				goTo: function(url) {
					window.history.pushState(null, null, url);
					this.onStateChange();
				},

				/**
				 * Force the browser to go to a certain url
				 *
				 * @memberOf Barba.Pjax
				 * @param {String} url
				 * @private
				 */
				forceGoTo: function(url) {
					window.location = url;
				},

				/**
				 * Load an url, will start an xhr request or load from the cache
				 *
				 * @memberOf Barba.Pjax
				 * @private
				 * @param  {String} url
				 * @return {Promise}
				 */
				load: function(url) {
					var deferred = Utils.deferred();
					var _this = this;
					var xhr;

					xhr = this.Cache.get(url);

					if (!xhr) {
						xhr = Utils.xhr(url);
						this.Cache.set(url, xhr);
					}

					xhr.then(
						function(data) {
							var container = _this.Dom.parseResponse(data);

							_this.Dom.putContainer(container);

							if (!_this.cacheEnabled)
								_this.Cache.reset();

							deferred.resolve(container);
						},
						function() {
							//Something went wrong (timeout, 404, 505...)
							_this.forceGoTo(url);

							deferred.reject();
						}
					);

					return deferred.promise;
				},

				/**
				 * Get the .href parameter out of an element
				 * and handle special cases (like xlink:href)
				 *
				 * @private
				 * @memberOf Barba.Pjax
				 * @param  {HTMLElement} el
				 * @return {String} href
				 */
				getHref: function(el) {
					if (!el) {
						return undefined;
					}

					if (el.getAttribute && typeof el.getAttribute('xlink:href') === 'string') {
						return el.getAttribute('xlink:href');
					}

					if (typeof el.href === 'string') {
						return el.href;
					}

					return undefined;
				},

				/**
				 * Callback called from click event
				 *
				 * @memberOf Barba.Pjax
				 * @private
				 * @param {MouseEvent} evt
				 */
				onLinkClick: function(evt) {
					var el = evt.target;

					//Go up in the nodelist until we
					//find something with an href
					while (el && !this.getHref(el)) {
						el = el.parentNode;
					}

					if (this.preventCheck(evt, el)) {
						evt.stopPropagation();
						evt.preventDefault();

						Dispatcher.trigger('linkClicked', el, evt);

						var href = this.getHref(el);
						this.goTo(href);
					}
				},

				/**
				 * Determine if the link should be followed
				 *
				 * @memberOf Barba.Pjax
				 * @param  {MouseEvent} evt
				 * @param  {HTMLElement} element
				 * @return {Boolean}
				 */
				preventCheck: function(evt, element) {
					if (!window.history.pushState)
						return false;

					var href = this.getHref(element);

					//User
					if (!element || !href)
						return false;

					//Middle click, cmd click, and ctrl click
					if (evt.which > 1 || evt.metaKey || evt.ctrlKey || evt.shiftKey || evt.altKey)
						return false;

					//Ignore target with _blank target
					if (element.target && element.target === '_blank')
						return false;

					//Check if it's the same domain
					if (window.location.protocol !== element.protocol || window.location.hostname !== element.hostname)
						return false;

					//Check if the port is the same
					if (Utils.getPort() !== Utils.getPort(element.port))
						return false;

					//Ignore case when a hash is being tacked on the current URL
					if (href.indexOf('#') > -1)
						return false;

					//Ignore case where there is download attribute
					if (element.getAttribute && typeof element.getAttribute('download') === 'string')
						return false;

					//In case you're trying to load the same page
					if (Utils.cleanLink(href) == Utils.cleanLink(location.href))
						return false;

					if (element.classList.contains(this.ignoreClassLink))
						return false;

					return true;
				},

				/**
				 * Return a transition object
				 *
				 * @memberOf Barba.Pjax
				 * @return {Barba.Transition} Transition object
				 */
				getTransition: function() {
					//User customizable
					return HideShowTransition;
				},

				/**
				 * Method called after a 'popstate' or from .goTo()
				 *
				 * @memberOf Barba.Pjax
				 * @private
				 */
				onStateChange: function() {
					var newUrl = this.getCurrentUrl();

					if (this.transitionProgress)
						this.forceGoTo(newUrl);

					if (this.History.currentStatus().url === newUrl)
						return false;

					this.History.add(newUrl);

					var newContainer = this.load(newUrl);
					var transition = Object.create(this.getTransition());

					this.transitionProgress = true;

					Dispatcher.trigger('initStateChange',
						this.History.currentStatus(),
						this.History.prevStatus()
					);

					var transitionInstance = transition.init(
						this.Dom.getContainer(),
						newContainer
					);

					newContainer.then(
						this.onNewContainerLoaded.bind(this)
					);

					transitionInstance.then(
						this.onTransitionEnd.bind(this)
					);
				},

				/**
				 * Function called as soon the new container is ready
				 *
				 * @memberOf Barba.Pjax
				 * @private
				 * @param {HTMLElement} container
				 */
				onNewContainerLoaded: function(container) {
					var currentStatus = this.History.currentStatus();
					currentStatus.namespace = this.Dom.getNamespace(container);

					Dispatcher.trigger('newPageReady',
						this.History.currentStatus(),
						this.History.prevStatus(),
						container,
						this.Dom.currentHTML
					);
				},

				/**
				 * Function called as soon the transition is finished
				 *
				 * @memberOf Barba.Pjax
				 * @private
				 */
				onTransitionEnd: function() {
					this.transitionProgress = false;

					Dispatcher.trigger('transitionCompleted',
						this.History.currentStatus(),
						this.History.prevStatus()
					);
				}
			};

			module.exports = Pjax;


			/***/ },
		/* 11 */
		/***/ function(module, exports, __webpack_require__) {

			var BaseTransition = __webpack_require__(4);

			/**
			 * Basic Transition object, wait for the new Container to be ready,
			 * scroll top, and finish the transition (removing the old container and displaying the new one)
			 *
			 * @private
			 * @namespace Barba.HideShowTransition
			 * @augments Barba.BaseTransition
			 */
			var HideShowTransition = BaseTransition.extend({
				start: function() {
					this.newContainerLoading.then(this.finish.bind(this));
				},

				finish: function() {
					document.body.scrollTop = 0;
					this.done();
				}
			});

			module.exports = HideShowTransition;


			/***/ },
		/* 12 */
		/***/ function(module, exports) {

			/**
			 * Object that is going to deal with DOM parsing/manipulation
			 *
			 * @namespace Barba.Pjax.Dom
			 * @type {Object}
			 */
			var Dom = {
				/**
				 * The name of the data attribute on the container
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @type {String}
				 * @default
				 */
				dataNamespace: 'namespace',

				/**
				 * Id of the main wrapper
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @type {String}
				 * @default
				 */
				wrapperId: 'barba-wrapper',

				/**
				 * Class name used to identify the containers
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @type {String}
				 * @default
				 */
				containerClass: 'barba-container',

				/**
				 * Full HTML String of the current page.
				 * By default is the innerHTML of the initial loaded page.
				 *
				 * Each time a new page is loaded, the value is the response of the xhr call.
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @type {String}
				 */
				currentHTML: document.documentElement.innerHTML,

				/**
				 * Parse the responseText obtained from the xhr call
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @private
				 * @param  {String} responseText
				 * @return {HTMLElement}
				 */
				parseResponse: function(responseText) {
					this.currentHTML = responseText;

					var wrapper = document.createElement('div');
					wrapper.innerHTML = responseText;

					var titleEl = wrapper.querySelector('title');

					if (titleEl)
						document.title = titleEl.textContent;

					return this.getContainer(wrapper);
				},

				/**
				 * Get the main barba wrapper by the ID `wrapperId`
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @return {HTMLElement} element
				 */
				getWrapper: function() {
					var wrapper = document.getElementById(this.wrapperId);

					if (!wrapper)
						throw new Error('Barba.js: wrapper not found!');

					return wrapper;
				},

				/**
				 * Get the container on the current DOM,
				 * or from an HTMLElement passed via argument
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @private
				 * @param  {HTMLElement} element
				 * @return {HTMLElement}
				 */
				getContainer: function(element) {
					if (!element)
						element = document.body;

					if (!element)
						throw new Error('Barba.js: DOM not ready!');

					var container = this.parseContainer(element);

					if (container && container.jquery)
						container = container[0];

					if (!container)
						throw new Error('Barba.js: no container found');

					return container;
				},

				/**
				 * Get the namespace of the container
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @private
				 * @param  {HTMLElement} element
				 * @return {String}
				 */
				getNamespace: function(element) {
					if (element && element.dataset) {
						return element.dataset[this.dataNamespace];
					} else if (element) {
						return element.getAttribute('data-' + this.dataNamespace);
					}

					return null;
				},

				/**
				 * Put the container on the page
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @private
				 * @param  {HTMLElement} element
				 */
				putContainer: function(element) {
					element.style.visibility = 'hidden';

					var wrapper = this.getWrapper();
					wrapper.appendChild(element);
				},

				/**
				 * Get container selector
				 *
				 * @memberOf Barba.Pjax.Dom
				 * @private
				 * @param  {HTMLElement} element
				 * @return {HTMLElement} element
				 */
				parseContainer: function(element) {
					return element.querySelector('.' + this.containerClass);
				}
			};

			module.exports = Dom;


			/***/ },
		/* 13 */
		/***/ function(module, exports, __webpack_require__) {

			var Utils = __webpack_require__(5);
			var Pjax = __webpack_require__(10);

			/**
			 * Prefetch
			 *
			 * @namespace Barba.Prefetch
			 * @type {Object}
			 */
			var Prefetch = {
				/**
				 * Class name used to ignore prefetch on links
				 *
				 * @memberOf Barba.Prefetch
				 * @type {String}
				 * @default
				 */
				ignoreClassLink: 'no-barba-prefetch',

				/**
				 * Init the event listener on mouseover and touchstart
				 * for the prefetch
				 *
				 * @memberOf Barba.Prefetch
				 */
				init: function() {
					if (!window.history.pushState) {
						return false;
					}

					document.body.addEventListener('mouseover', this.onLinkEnter.bind(this));
					document.body.addEventListener('touchstart', this.onLinkEnter.bind(this));
				},

				/**
				 * Callback for the mousehover/touchstart
				 *
				 * @memberOf Barba.Prefetch
				 * @private
				 * @param  {Object} evt
				 */
				onLinkEnter: function(evt) {
					var el = evt.target;

					while (el && !Pjax.getHref(el)) {
						el = el.parentNode;
					}

					if (!el || el.classList.contains(this.ignoreClassLink)) {
						return;
					}

					var url = Pjax.getHref(el);

					//Check if the link is elegible for Pjax
					if (Pjax.preventCheck(evt, el) && !Pjax.Cache.get(url)) {
						var xhr = Utils.xhr(url);
						Pjax.Cache.set(url, xhr);
					}
				}
			};

			module.exports = Prefetch;


			/***/ }
		/******/ ])
});
;

(function( $ ) {

	$.fn.getStyleObject = function() {
		var dom = this.get( 0 ),
			returns = {},
			style;

		if ( window.getComputedStyle ) {
			var camelize = function( a, b ) {
				return b.toUpperCase();
			};
			style = window.getComputedStyle( dom, null );
			for ( var i = 0, l = style.length; i < l; i ++ ) {
				var prop = style[i];
				var camel = prop.replace( /\-([a-z])/g, camelize );
				var val = style.getPropertyValue( prop );
				returns[camel] = val;
			}
			;
			return returns;
		}
		;
		if ( style = dom.currentStyle ) {
			for ( var prop in style ) {
				returns[prop] = style[prop];
			}
			return returns;
		}
		return this.css();
	}

	$.fn.copyCSS = function( source ) {
		var styles = $( source ).getStyleObject();
		this.css( styles );
	}

	$.fn.resizeselect = function( settings ) {
		return this.each( function() {

			$( this ).change( function() {
				var $this = $( this );

				// create test element
				var text = $this.find( "option:selected" ).text();
				var $test = $( "<span>" ).html( text );

				// add to body, get width, and get out
				$test.appendTo( 'body' ).copyCSS( $this );
				$test.css( 'width', 'auto' );
				var width = $test.width();
				$test.remove();

				// set select width
				$this.width( width );

				// run on start
			} ).change();
		} );
	};

})( jQuery, window );

(function( $, window, document, undefined ) {

var AjaxLoading = function() {

	var _this = this;

	_this.ev = $({});

	if ( typeof Barba === "undefined" ) {
		return;
	}

	var ignored = ['.pdf', '.doc', '.eps', '.png', '.jpg', '.jpeg', '.zip', 'admin', 'wp-', 'wp-admin', 'feed', '#', '&add-to-cart=', '?add-to-cart=', '?remove_item'],
		barbaPreventCheck = Barba.Pjax.preventCheck;

	Barba.Pjax.preventCheck = function( ev, element ) {

		if ( !element || !element.href ) {
			return false;
		} else {
			for ( var i = ignored.length - 1; i >= 0; i-- ) {
				if ( element.href.indexOf( ignored[i] ) > -1 ) {
					return false;
				}
			}
		}

		return barbaPreventCheck.call( Barba.Pjax, ev, element );
	};

	/**
	 * Next step, you have to tell Barba to use the new Transition
	 */
	Barba.Pjax.getTransition = function() {
		/**
		 * Here you can use your own logic!
		 * For example you can use different Transition based on the current page or link...
		 */

		return Barba.BaseTransition.extend({
			start: function() {
				/**
				 * This function is automatically called as soon the Transition starts
				 * this.newContainerLoading is a Promise for the loading of the new container
				 * (Barba.js also comes with an handy Promise polyfill!)
				 */
				Promise
				.all( [this.newContainerLoading, this.fadeOut()] )
				.then( this.fadeIn.bind( this ) );
			},

			fadeOut: function() {
				/**
				 * this.oldContainer is the HTMLElement of the old Container
				 */
				var _that = this,
					$old = $( _that.oldContainer );

				performance.mark( "ajaxTransition:start" );

				$old.find('video').each( function() {
					this.pause(); // can't hurt
					delete this; // @sparkey reports that this did the trick (even though it makes no sense!)
					this.src = ""; // empty source
					this.load();
					$( this ).remove(); // this is probably what actually does the trick
				});

				_this.ev.trigger( 'beforeOut', $( _that.newContainer ) );


				return new Promise( function( resolve ) {
					// alternate syntax for adding a callback
					setTimeout(function() {
						resolve( true );
						_this.ev.trigger( 'afterOut', $( _that.newContainer ) );
					}, 1000);
				});
			},

			fadeIn: function() {
				var _that = this;

				/**
				 * this.newContainer is the HTMLElement of the new Container
				 * At this stage newContainer is on the DOM (inside our #barba-container and with visibility: hidden)
				 * Please note, newContainer is available just after newContainerLoading is resolved!
				 */
				Barba.Pjax.Cache.data[Barba.HistoryManager.currentStatus().url].then( function(data) {
					// get data and replace the body tag with a nobody tag
					// because jquery strips the body tag when creating objects from data
					var $newBody = $( data.replace(/(<\/?)body( .+?)?>/gi, '$1NOTBODY$2>', data) ).filter( 'notbody' );

					// need to get the id and edit string from the data attributes
					var curPostID = $newBody.data('curpostid'),
						curPostTax = $newBody.data( 'curtaxonomy' ),
						curPostEditString = $newBody.data( 'curpostedit' );

					// Put the new body classes
					$( 'body' ).attr( 'class', $newBody.attr( 'class' ) );

					// Fix the admin bar, including modifying the body classes and attributes
					Util.adminBarEditFix( curPostID, curPostEditString, curPostTax );

					window.scrollTo(0, 0);

					_this.ev.trigger( 'beforeIn', $( _that.newContainer ) );

					_that.done();

					// find and initialize Tiled Galleries via Jetpack
					if ( typeof tiledGalleries !== "undefined" ) {
						tiledGalleries.findAndSetupNewGalleries();
					}

					// lets do some Google Analytics Tracking
					if ( window._gaq ) {
						_gaq.push( ['_trackPageview'] );
					}

					_this.ev.trigger( 'afterIn', $( _that.newContainer ) );

					performance.mark( "ajaxTransition:end" );
					performance.measure( 'ajaxTransition', 'ajaxTransition:start', 'ajaxTransition:end' );
				});
			}
		});
	};

	Barba.Pjax.start();
};

var Gallery = function( $container ) {

	var _this = this;

	_this.$el = $();

	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	$container.find( '.c-gallery--packed' ).each( function( i, obj ) {
		var $gallery = $( obj );

		if ( ! $gallery.children().length ) {
			return;
		}

		_this.$el = _this.$el.add( $gallery );

		$gallery.data( 'offset', $gallery.offset() );

		_this.refresh( $gallery );
	} );
};

Gallery.prototype.refresh = function( $galleries ) {
	var _this = this;

	if ( typeof $galleries === "undefined" ) {
		$galleries = _this.$el;
	}

	$galleries.each( function( i, obj ) {

		var $gallery = $( obj );

		var minWidth = $gallery.children()[0].getBoundingClientRect().width;

		$gallery.children().each( function() {
			var width = this.getBoundingClientRect().width;

			if ( width < minWidth ) {
				minWidth = width;
			}
		} );

		$gallery.masonry( {
			isAnimated: false,
			columnWidth: minWidth
		} );

	} );

};

Logo = function( scrollY ) {

	this.$clone = null;
	this.distance = null;
	this.initialized = false;

	this.$logo = $( '.c-branding' ).closest( '.c-navbar__zone' ).addClass( 'c-navbar__zone--branding' );
	this.$clone = this.$logo.clone().appendTo( '.c-navbar' );

//	this.onResize( scrollY );
};

Logo.prototype.onResize = function( scrollY ) {

	var that = this;

	scrollY = scrollY || (window.pageYOffset || document.documentElement.scrollTop) - (document.documentElement.clientTop || 0);

	if ( that.$logo.length ) {

		that.$logo.imagesLoaded(function() {

			if ( that.$clone === null ) {
				that.$clone = that.$logo.clone().appendTo( '.mobile-logo' );
			} else {
				that.$clone.removeAttr( 'style' );
			}

			that.logoMid = that.$logo.offset().top + that.$logo.height() / 2;
			that.cloneMid = that.$clone.offset().top + that.$clone.height() / 2 - scrollY;
			that.distance = that.logoMid - that.cloneMid;

			that.initialized = true;

			that.update( scrollY );

			that.$clone.css( 'opacity', 1 );

		});
	}
};

Logo.prototype.update = function( scrollY ) {

	if ( ! this.initialized ) {
		return;
	}

	if ( this.distance < scrollY ) {
		this.$clone.css( 'transform', 'none' );
		return;
	}

	this.$clone.css( 'transform', 'translateY(' + ( this.distance - scrollY ) + 'px)' );
};

var Navbar = function() {
	this.$handle = $( '#menu-toggle' );
	this.bindEvents();
};

Navbar.prototype.bindEvents = function() {

	$( '.c-navbar' ).on( 'click', 'a', function( e ) {

		var $link = $( this ),
			$item = $link.parent(),
			$submenu = $link.siblings( 'ul' );

		if ( $submenu.length && ! $item.hasClass( 'is-active' ) ) {
			e.preventDefault();
			e.stopPropagation();
			$item.addClass( 'is-active' );
		}

	} );

};

Navbar.prototype.open = function() {
	this.$handle.prop( 'checked', true );
	this.$handle.trigger( 'change' );
};

Navbar.prototype.close = function() {
	this.$handle.prop( 'checked', false );
	this.$handle.trigger( 'change' );
};

Navbar.prototype.onChange = function() {

	var $body = $( 'body' );

	if ( this.$handle.prop( 'checked' ) ) {
		// Open navbar and prevent scrolling
		$body.width( $body.width() );
		$body.css( 'overflow', 'hidden' );
	} else {
		// Close navigation and allow scrolling
		$body.css( 'overflow', '' );

		// Close all open submenus
		$( '.menu-item' ).removeClass( 'hover' );
	}

};

var Parallax = function( selector, options ) {
	this.disabled = false;
	this.selector = selector;
	this.options = options;
};

Parallax.prototype.init = function( $container ) {
	$container = $container || $( 'body' );

	if ( this.disabled === false ) {
		$container.find( this.selector ).rellax( this.options );
	}
};

Parallax.prototype.disable = function() {
	this.disabled = true;
	$( this.selector ).rellax( "destroy" );
};

Parallax.prototype.enable = function() {
	this.disabled = false;
	$( this.selector ).rellax( this.options );
};

/*!
 * pixelgradeTheme v1.0.2
 * Copyright (c) 2017 PixelGrade http://www.pixelgrade.com
 * Licensed under MIT http://www.opensource.org/licenses/mit-license.php/
 */
var pixelgradeTheme = function() {

	var _this = this,
		lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0),
		windowWidth = window.innerWidth,
		windowHeight = window.innerHeight,
		orientation = windowWidth > windowHeight ? 'landscape' : 'portrait';

	_this.ev = $( {} );
	_this.frameRendered = false;
	_this.debug = false;

	_this.update = function() {

	};

	_this.onScroll = function() {
		if ( _this.frameRendered === false ) {
			return;
		}
		lastScrollY = (window.pageYOffset || document.documentElement.scrollTop)  - (document.documentElement.clientTop || 0);
		_this.frameRendered = false;
	};

	_this.getScroll = function() {
		return lastScrollY;
	};

	_this.getWindowWidth = function() {
		return windowWidth;
	};

	_this.getWindowHeight = function() {
		return windowHeight;
	};

	_this.getOrientation = function() {
		return orientation;
	};

	_this.onResize = function() {
		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;

		var newOrientation = windowWidth > windowHeight ? 'landscape' : 'portrait';

		_this.debouncedResize();

		if ( orientation !== newOrientation ) {
			_this.debouncedOrientationChange();
		}

		orientation = newOrientation;
	};

	_this.debouncedResize = Util.debounce(function() {
		$( window ).trigger( 'pxg:resize' );
	}, 300);

	_this.debouncedOrientationChange = Util.debounce(function() {
		$( window ).trigger( 'pxg:orientationchange' );
	}, 300);

	_this.renderLoop = function() {
		if ( _this.frameRendered === false ) {
			_this.ev.trigger( 'render' );
		}
		requestAnimationFrame( function() {
			_this.update();
			_this.renderLoop();
			_this.frameRendered = true;
			_this.ev.trigger( 'afterRender' );
		} );
	};

	_this.eventHandlers = function() {
		$( document ).ready( _this.onReady );
		$( window )
		.on( 'scroll', _this.onScroll )
		.on( 'resize', _this.onResize )
		.on( 'load', _this.onLoad );
	};

	_this.eventHandlers();
	_this.renderLoop();
};


pixelgradeTheme.prototype.onReady = function() {
	$( 'html' ).addClass( 'is-ready' );
};

pixelgradeTheme.prototype.onLoad = function() {
	$( 'html' ).addClass( 'is-loaded' );
};

pixelgradeTheme.prototype.log = function( message ) {
	if ( this.debug === true ) {
		console.log( message )
	}
};

var readingProgress = function( $element, $target, scrollY ) {
	this.$element = $element || $( '.entry-content' );
	this.$target = $target || $( '.js-reading-progress' );
	this.scrollY = scrollY || (window.pageYOffset || document.documentElement.scrollTop) - (document.documentElement.clientTop || 0);
};

readingProgress.prototype.onResize = function( windowWidth, windowHeight ) {
	windowWidth = windowWidth || window.innerWidth;
	windowHeight = windowHeight || window.innerHeight;

	if ( typeof $element === "undefined" ) {
		return;
	}

	this.start = this.$element.offset().top;
	this.stop = this.start + this.$element.outerHeight();
};

readingProgress.prototype.onScroll = function( scrollY ) {
	var that = this,
		progress = 0;

	scrollY = scrollY || (window.pageYOffset || document.documentElement.scrollTop) - (document.documentElement.clientTop || 0);
	progress = (scrollY - this.start) / (this.stop - this.start);
	progress = Math.min(Math.max(progress, 0), 1);

	this.$target.css( 'width', progress * 100 + '%' );
};

/*!
 * pixelgradeTheme v1.0.2
 * Copyright (c) 2017 PixelGrade http://www.pixelgrade.com
 * Licensed under MIT http://www.opensource.org/licenses/mit-license.php/
 */
var Slider = function() {

	this.options = {
		dots: true,
		infinite: true,
		speed: 600,
		fade: true,
		useTransform: false,
		ease: 'easeInOutCirc'
	};

};

Slider.prototype.init = function( $container ) {

	var _this = this,
		$sliders = $container.find( '.c-hero__slider, .js-pixslider' );

	$sliders.each( function( i, obj ) {

		var $slider = $( obj ),
			autoplay = typeof $slider.data( 'autoplay' ) !== "undefined",
			autoplaySpeed = typeof $slider.data( 'autoplay-delay' ) == "number" ? $slider.data( 'autoplay-delay' ) : 2000;

		if ( autoplay ) {
			$.extend( _this.options, {
				autoplay: autoplay,
				autoplaySpeed: autoplaySpeed
			} );
		}

		if ( $slider.is( '.c-hero__slider' ) && $( '.js-front-page-sidebar' ).length ) {
			$.extend( _this.options, {
				dots: true
			} );
		}

		if ( $slider.children().length > 1 ) {
			$slider.slick( _this.options );
			$slider.find( '.slick-slide' ).first().focus();
		}

	} );

};

var Util = {
	/**
	 *
	 * @returns {boolean}
	 */
	isTouch: function() {
		return ! ! (
			"ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch
		);
	},

	handleCustomCSS: function( $container ) {
		var $elements = typeof $container !== "undefined" ? $container.find( "[data-css]" ) : $( "[data-css]" );

		if ( $elements.length ) {
			$elements.each( function( i, obj ) {
				var $element = $( obj ),
					css = $element.data( 'css' );

				if ( typeof css !== "undefined" ) {
					$element.replaceWith( '<style type="text/css">' + css + '</style>' );
				}
			} );
		}
	},


	/**
	 * Search every image that is alone in a p tag and wrap it
	 * in a figure element to behave like images with captions
	 *
	 * @param $container
	 */
	unwrapImages: function( $container ) {

		$container = typeof $container !== "undefined" ? $container : $( 'body' );

		$container.find( 'p > img:first-child:last-child' ).each( function( i, obj ) {
			var $image = $( obj ),
				className = $image.attr( 'class' ),
				$p = $image.parent();

			if ( $.trim( $p.text() ).length ) {
				return;
			}

			$image
			.removeAttr( 'class' )
			.unwrap()
			.wrap( '<figure />' )
			.parent()
			.attr( 'class', className );
		} );

		$container.find( '.entry-content > .gallery' ).wrap( '<div class="gallery-wrapper" />' );

	},

	wrapEmbeds: function( $container ) {
		$container = typeof $container !== "undefined" ? $container : $( 'body' );
		$container.children( 'iframe, embed, object' ).wrap( '<p>' );
	},

	/**
	 * Initialize video elements on demand from placeholders
	 *
	 * @param $container
	 */
	handleVideos: function( $container ) {
		$container = typeof $container !== "undefined" ? $container : $( 'body' );

		$container.find( '.video-placeholder' ).each( function( i, obj ) {
			var $placeholder = $( obj ),
				video = document.createElement( 'video' ),
				$video = $( video ).addClass( 'c-hero__video' );

			// play as soon as possible
			video.onloadedmetadata = function() {
				video.play();
			};

			video.src = $placeholder.data( 'src' );
			video.poster = $placeholder.data( 'poster' );
			video.muted = true;
			video.loop = true;

			$placeholder.replaceWith( $video );
		} );
	},

	smoothScrollTo: function( to, duration, easing ) {
		to = to || 0;
		duration = duration || 1000;
		easing = easing || 'swing';

		$( "html, body" ).stop().animate( {
			scrollTop: to
		}, duration, easing );

	},

	// Returns a function, that, as long as it continues to be invoked, will not
	// be triggered. The function will be called after it stops being called for
	// N milliseconds. If `immediate` is passed, trigger the function on the
	// leading edge, instead of the trailing.
	debounce: function( func, wait, immediate ) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if ( ! immediate ) {
					func.apply( context, args );
				}
			};
			var callNow = immediate && ! timeout;
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
			if ( callNow ) {
				func.apply( context, args );
			}
		};
	},

	// Returns a function, that, when invoked, will only be triggered at most once
	// during a given window of time. Normally, the throttled function will run
	// as much as it can, without ever going more than once per `wait` duration;
	// but if you'd like to disable the execution on the leading edge, pass
	// `{leading: false}`. To disable execution on the trailing edge, ditto.
	throttle: function( callback, limit ) {
		var wait = false;
		return function() {
			if ( ! wait ) {
				callback.call();
				wait = true;
				setTimeout( function() {
					wait = false;
				}, limit );
			}
		}
	},

	mq: function( direction, string ) {
		var $temp = $( '<div class="u-mq-' + direction + '-' + string + '">' ).appendTo( 'body' ),
			response = $temp.is( ':visible' );

		$temp.remove();
		return response;
	},

	below: function( string ) {
		return this.mq( 'below', string );
	},

	above: function( string ) {
		return this.mq( 'above', string );
	},

	getParamFromURL: function( param, url ) {
		var parameters = (
			url.split( '?' )
		)[1];

		if ( typeof parameters === "undefined" ) {
			return parameters;
		}

		parameters = parameters.split( '&' );

		for ( var i = 0; i < parameters.length; i ++ ) {
			var parameter = parameters[i].split( '=' );
			if ( parameter[0] === param ) {
				return parameter[1];
			}
		}
	},

	reloadScript: function( filename ) {
		var $old = $( 'script[src*="' + filename + '"]' ),
			$new = $( '<script>' ),
			src = $old.attr( 'src' );

		if ( ! $old.length ) {
			return;
		}

		$old.replaceWith( $new );
		$new.attr( 'src', src );
	},

	// here we change the link of the Edit button in the Admin Bar
	// to make sure it reflects the current page
	adminBarEditFix: function( id, editString, taxonomy ) {
		// get the admin ajax url and clean it
		var baseEditURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'post.php' ),
			baseEditTaxURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'edit-tags.php' ),
			$editButton = $( '#wp-admin-bar-edit a' );

		if ( ! empty( $editButton ) ) {
			if ( id !== undefined && editString !== undefined ) { //modify the current Edit button
				if ( !empty( taxonomy ) ) { //it seems we need to edit a taxonomy
					$editButton.attr( 'href', baseEditTaxURL + '?tag_ID=' + id + '&taxonomy=' + taxonomy + '&action=edit' );
				} else {
					$editButton.attr( 'href', baseEditURL + '?post=' + id + '&action=edit' );
				}
				$editButton.html( editString );
			} else { // we have found an edit button but right now we don't need it anymore since we have no id
				$( '#wp-admin-bar-edit' ).remove();
			}
		} else { // upss ... no edit button
			// lets see if we need one
			if ( id !== undefined && editString !== undefined ) { //we do need one after all
				//locate the New button because we need to add stuff after it
				var $newButton = $( '#wp-admin-bar-new-content' );

				if ( !empty( $newButton ) ) {
					if ( !empty( taxonomy ) ) { //it seems we need to generate a taxonomy edit thingy
						$newButton.after( '<li id="wp-admin-bar-edit"><a class="ab-item dJAX_internal" href="' + baseEditTaxURL + '?tag_ID=' + id + '&taxonomy=' + taxonomy + '&action=edit">' + editString + '</a></li>' );
					} else { //just a regular edit
						$newButton.after( '<li id="wp-admin-bar-edit"><a class="ab-item dJAX_internal" href="' + baseEditURL + '?post=' + id + '&action=edit">' + editString + '</a></li>' );
					}
				}
			}
		}

		//Also we need to fix the (no-)customize-support class on body by running the WordPress inline script again
		// The original code is generated by the wp_customize_support_script() function in wp-includes/theme.php @3007
		var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');

		// No CORS request
		request = true;

		b[c] = b[c].replace( rcs, ' ' );
		// The customizer requires postMessage and CORS (if the site is cross domain)
		b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;

		//Plus, we need to change the url of the Customize button to the current url
		var $customizeButton = $( '#wp-admin-bar-customize a' ),
			baseCustomizeURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php','customize.php' );
		if ( ! empty( $customizeButton ) ) {
			$customizeButton.attr( 'href', baseCustomizeURL + '?url=' + encodeURIComponent( window.location.href ) );
		}

	},

	//similar to PHP's empty function
	empty: function( data ) {
		if ( typeof( data ) == 'number' || typeof( data ) == 'boolean' ) {
			return false;
		}

		if ( typeof( data ) == 'undefined' || data === null ) {
			return true;
		}

		if ( typeof( data.length ) != 'undefined' ) {
			return data.length === 0;
		}

		var count = 0;

		for ( var i in data ) {
			// if (data.hasOwnProperty(i))
			//
			// This doesn't work in ie8/ie9 due the fact that hasOwnProperty works only on native objects.
			// http://stackoverflow.com/questions/8157700/object-has-no-hasownproperty-method-i-e-its-undefined-ie8
			//
			// for hosts objects we do this
			if ( Object.prototype.hasOwnProperty.call( data, i ) ) {
				count ++;
			}
		}
		return count === 0;
	},

	// here we change the link of the Edit button in the Admin Bar
	// to make sure it reflects the current page
	adminBarEditFix: function( id, editString, taxonomy ) {
		// get the admin ajax url and clean it
		var baseEditURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'post.php' ),
			baseEditTaxURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'edit-tags.php' ),
			$editButton = $( '#wp-admin-bar-edit a' );

		if ( ! Util.empty( $editButton ) ) {
			if ( id !== undefined && editString !== undefined ) { //modify the current Edit button
				if ( ! Util.empty( taxonomy ) ) { //it seems we need to edit a taxonomy
					$editButton.attr( 'href', baseEditTaxURL + '?tag_ID=' + id + '&taxonomy=' + taxonomy + '&action=edit' );
				} else {
					$editButton.attr( 'href', baseEditURL + '?post=' + id + '&action=edit' );
				}
				$editButton.html( editString );
			} else { // we have found an edit button but right now we don't need it anymore since we have no id
				$( '#wp-admin-bar-edit' ).remove();
			}
		} else { // upss ... no edit button
			// lets see if we need one
			if ( id !== undefined && editString !== undefined ) { //we do need one after all
				//locate the New button because we need to add stuff after it
				var $newButton = $( '#wp-admin-bar-new-content' );

				if ( ! Util.empty( $newButton ) ) {
					if ( ! Util.empty( taxonomy ) ) { //it seems we need to generate a taxonomy edit thingy
						$newButton.after( '<li id="wp-admin-bar-edit"><a class="ab-item dJAX_internal" href="' + baseEditTaxURL + '?tag_ID=' + id + '&taxonomy=' + taxonomy + '&action=edit">' + editString + '</a></li>' );
					} else { //just a regular edit
						$newButton.after( '<li id="wp-admin-bar-edit"><a class="ab-item dJAX_internal" href="' + baseEditURL + '?post=' + id + '&action=edit">' + editString + '</a></li>' );
					}
				}
			}
		}

		//Also we need to fix the (no-)customize-support class on body by running the WordPress inline script again
		// The original code is generated by the wp_customize_support_script() function in wp-includes/theme.php @3007
		var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp( '(^|\\s+)(no-)?' + cs + '(\\s+|$)' );

		// No CORS request
		request = true;

		b[c] = b[c].replace( rcs, ' ' );
		// The customizer requires postMessage and CORS (if the site is cross domain)
		b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;

		//Plus, we need to change the url of the Customize button to the current url
		var $customizeButton = $( '#wp-admin-bar-customize a' ),
			baseCustomizeURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'customize.php' );
		if ( ! Util.empty( $customizeButton ) ) {
			$customizeButton.attr( 'href', baseCustomizeURL + '?url=' + encodeURIComponent( window.location.href ) );
		}

	},

	/**
	 * returns version of IE or false, if browser is not Internet Explorer
	 */
	getIEversion: function() {
		var ua = window.navigator.userAgent;

		// Test values; Uncomment to check result …

		// IE 10
		// ua = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)';

		// IE 11
		// ua = 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko';

		// Edge 12 (Spartan)
		// ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';

		// Edge 13
		// ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';

		var msie = ua.indexOf('MSIE ');
		if (msie > 0) {
			// IE 10 or older => return version number
			return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
		}

		var trident = ua.indexOf('Trident/');
		if (trident > 0) {
			// IE 11 => return version number
			var rv = ua.indexOf('rv:');
			return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
		}

		var edge = ua.indexOf('Edge/');
		if (edge > 0) {
			// Edge (IE 12+) => return version number
			return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
		}

		// other browser
		return false;
	}

};


var Boilerplate = new pixelgradeTheme(),
	resizeEvent = "ontouchstart" in window && "onorientationchange" in window ? "pxg:orientationchange" : "pxg:resize",
	$window = $( window ),
	$html = $( 'html' ),
	$body = $( 'body' ),
	ieVersion = Util.getIEversion();

Boilerplate.init = function() {
	Boilerplate.log( 'Boilerplate.init' );

	Boilerplate.Slider = new Slider();
	Boilerplate.Logo = new Logo();
	Boilerplate.Navbar = new Navbar();

	Boilerplate.Parallax = new Parallax( '.c-hero__background, .c-hero__background-mask', {
		bleed: 20,
		scale: 1.2,
		container: '.c-hero__background-mask'
	} );

	Boilerplate.Parallax.disabled = "ontouchstart" in window && "onorientationchange" in window;

	var $body = $( 'body' );

	if ( ! $body.is( '.customizer-preview' ) && typeof $body.data( 'ajaxloading' ) !== 'undefined' ) {
		Boilerplate.initializeAjax();
	}

	// on document.ready
	$( function() {
		Boilerplate.handleContent();
		Boilerplate.eventHandlersOnce();
		Boilerplate.eventHandlers();
		Boilerplate.adjustLayout();
		Boilerplate.fadeIn();
	} );
};

Boilerplate.update = function() {
	Boilerplate.log( 'Boilerplate.update' );

	Boilerplate.readingProgress.onScroll( Boilerplate.getScroll() );
};

Boilerplate.handleScrollArrow = function( $container ) {
	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	var $arrow = $container.find( '.c-hero__scroll-arrow' ).first(),
		$hero = $arrow.closest( '.c-hero' ),
		top;

	if ( ! $arrow.length ) {
		return;
	}

	top = $hero.offset().top + $hero.outerHeight() - $( '.u-site-header-sticky .site-header' ).outerHeight()

	$arrow.off( 'click .scroll' );
	$arrow.on( 'click .scroll', function() {
		Util.smoothScrollTo( top, 500 );
	} );
};

Boilerplate.adjustLayout = function() {
	performance.mark( "adjustLayout:start" );

	Boilerplate.log( 'Boilerplate.adjustLayout' );
	Boilerplate.readingProgress.onResize();

	Boilerplate.Gallery = new Gallery();

	// use header height as spacing measure for specific elements
	var $updatable = $( '.js-header-height-padding-top' ),
		headerHeight = $( '.c-navbar' ).outerHeight() || $( '.c-navbar__middle' ).outerHeight();

	// set padding top to certain elements which is equal to the header height
	$updatable.css( 'paddingTop', headerHeight );

	performance.mark( 'adjustLayout:end' );
	performance.measure( 'adjustLayout', 'adjustLayout:start', 'adjustLayout:end' );
};

Boilerplate.handleContent = function( $container ) {
	performance.mark( "handleContent:start" );

	Boilerplate.log( 'Boilerplate.handleContent', $container );

	Boilerplate.readingProgress = new readingProgress();

	$container = typeof $container !== 'undefined' ? $container : $( 'body' );

	Util.unwrapImages( $container.find( '.entry-content' ) );
	Util.wrapEmbeds( $container.find( '.entry-content' ) );
	Util.handleVideos( $container );
	Util.handleCustomCSS( $container );

	Boilerplate.handleScrollArrow( $container );

	Boilerplate.Parallax.init( $container );

	$container.find( '#comments-toggle' ).prop( 'checked', window.location.href.indexOf( '#comment' ) !== -1 );

	$container.find( '.js-taxonomy-dropdown' ).each( function( i, obj ) {
		var $select = $( obj ),
			selected = $select.find( '[selected]' ).first().attr( 'value' );

		$select.val( selected ).resizeselect().addClass( 'is-loaded' );
	} );

	performance.mark( "handleContent:end" );
	performance.measure( 'handleContent', 'handleContent:start', 'handleContent:end' );
};

Boilerplate.eventHandlersOnce = function() {
	Boilerplate.log( 'Boilerplate.eventHandlersOnce' );

	$( window ).on( resizeEvent, Boilerplate.adjustLayout );
	$( window ).on( 'beforeunload', Boilerplate.fadeOut );

	$( 'body' ).on( 'change', '.js-taxonomy-dropdown.is-loaded', function( e ) {
		var destination = this.value;

		if ( typeof destination !== "undefined" && destination !== "#" ) {
			if ( typeof Boilerplate.Ajax !== "undefined" && typeof Barba !== "undefined" && typeof Barba.Pjax !== "undefined" ) {
				Barba.Pjax.goTo( destination );
			} else {
				window.location.href = destination;
			}
		}
	} );

	Boilerplate.ev.on( 'render', Boilerplate.update );
};

Boilerplate.eventHandlers = function( $container ) {
	Boilerplate.log( 'Boilerplate.eventHandlers', $container );

	$container = typeof $container !== 'undefined' ? $container : $( 'body' );

	// add every image on the page the .is-loaded class
	// after the image has actually loaded
	$container.find( 'img' ).each( function( i, obj ) {
		var $each = $( obj );
		$each.imagesLoaded( function() {
			$each.addClass( 'is-loaded' );
		} );
	} );

	$container.find( '.u-gallery-type--masonry' ).each( function( i, obj ) {
		var $gallery = $( obj );

		$gallery.imagesLoaded( function() {
			$gallery.masonry( {
				transitionDuration: 0
			} );
		} );
	} );
};

Boilerplate.initializeAjax = function() {
	Boilerplate.log( 'Boilerplate.initializeAjax' );

	Boilerplate.Ajax = new AjaxLoading();

	Boilerplate.Ajax.ev.on( 'beforeOut', function( ev, container ) {
		Boilerplate.Navbar.close();
		Boilerplate.fadeOut();
	} );

	Boilerplate.Ajax.ev.on( 'afterOut', function( ev, container ) {
		$html.addClass( 'no-transitions' );
	} );

	Boilerplate.Ajax.ev.on( 'afterIn', function( ev, container ) {
		var $container = $( container );

		// Util.reloadScript( 'related-posts.js' );

		Boilerplate.handleContent( $container );
		Boilerplate.eventHandlers( $container );
		Boilerplate.adjustLayout();
		Boilerplate.update();
		Boilerplate.fadeIn();
	} );
};

Boilerplate.fadeOut = function() {
	Boilerplate.log( 'Boilerplate.fadeOut' );

	$html.removeClass( 'fade-in' ).addClass( 'fade-out' );
};

Boilerplate.fadeIn = function() {
	Boilerplate.log( 'Boilerplate.fadeIn' );

	$html.removeClass( 'fade-out no-transitions' ).addClass( 'fade-in' );
};

$.Boilerplate = Boilerplate;
$.Boilerplate.init();

performance.mark( "app:init" );

$( function() {
	performance.mark( "app:ready" );
	performance.measure( 'Ready', 'app:start', 'app:ready' );
} );

$window.load( function() {
	performance.mark( "app:load" );
	performance.measure( 'Load', 'app:start', 'app:load' );
} );

})( jQuery, window, document );
