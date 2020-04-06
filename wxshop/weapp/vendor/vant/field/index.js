"use strict";

var _component = require('./../common/component.js');

var _props = require('./props.js');

(0, _component.VantComponent)({
  field: true,
  classes: ['input-class', 'right-icon-class'],
  props: Object.assign(Object.assign(Object.assign(Object.assign({}, _props.commonProps), _props.inputProps), _props.textareaProps), {
    size: String,
    icon: String,
    label: String,
    error: Boolean,
    center: Boolean,
    isLink: Boolean,
    leftIcon: String,
    rightIcon: String,
    autosize: [Boolean, Object],
    readonly: {
      type: Boolean,
      observer: 'setShowClear'
    },
    required: Boolean,
    iconClass: String,
    clearable: {
      type: Boolean,
      observer: 'setShowClear'
    },
    clickable: Boolean,
    inputAlign: String,
    customStyle: String,
    errorMessage: String,
    arrowDirection: String,
    showWordLimit: Boolean,
    errorMessageAlign: String,
    border: {
      type: Boolean,
      value: true
    },
    titleWidth: {
      type: String,
      value: '90px'
    }
  }),
  data: {
    focused: false,
    innerValue: '',
    showClear: false
  },
  created: function created() {
    this.value = this.data.value;
    this.setData({
      innerValue: this.value
    });
  },
  methods: {
    onInput: function onInput(event) {
      var _ref = event.detail || {},
          _ref$value = _ref.value,
          value = _ref$value === void 0 ? '' : _ref$value;

      this.value = value;
      this.setShowClear();
      this.emitChange();
    },
    onFocus: function onFocus(event) {
      this.focused = true;
      this.setShowClear();
      this.$emit('focus', event.detail);
    },
    onBlur: function onBlur(event) {
      this.focused = false;
      this.setShowClear();
      this.$emit('blur', event.detail);
    },
    onClickIcon: function onClickIcon() {
      this.$emit('click-icon');
    },
    onClear: function onClear() {
      var _this = this;

      this.setData({
        innerValue: ''
      });
      this.value = '';
      this.setShowClear();
      wx.nextTick(function () {
        _this.emitChange();

        _this.$emit('clear', '');
      });
    },
    onConfirm: function onConfirm(event) {
      var _ref2 = event.detail || {},
          _ref2$value = _ref2.value,
          value = _ref2$value === void 0 ? '' : _ref2$value;

      this.value = value;
      this.setShowClear();
      this.$emit('confirm', value);
    },
    setValue: function setValue(value) {
      this.value = value;
      this.setShowClear();

      if (value === '') {
        this.setData({
          innerValue: ''
        });
      }

      this.emitChange();
    },
    onLineChange: function onLineChange(event) {
      this.$emit('linechange', event.detail);
    },
    onKeyboardHeightChange: function onKeyboardHeightChange(event) {
      this.$emit('keyboardheightchange', event.detail);
    },
    emitChange: function emitChange() {
      this.$emit('input', this.value);
      this.$emit('change', this.value);
    },
    setShowClear: function setShowClear() {
      var _this$data = this.data,
          clearable = _this$data.clearable,
          readonly = _this$data.readonly;
      var focused = this.focused,
          value = this.value;
      this.setData({
        showClear: clearable && focused && !!value && !readonly
      });
    },
    noop: function noop() {}
  }
});