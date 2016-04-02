/**
 * @namespace 本地存储的方法
 * @version 1.1.0
 * @author: huangdijia
 */
define(function(require, exports) {
  
  var localStorage  = window.localStorage || {
    id: 'addcn_ls_data',
    init: function(){
      $('input#'+this.id).size() || $('body').append('<input tpye="text" id="'+this.id+'" style="display:none;" />');
    },
    getInstance: function(){
      if(typeof window._localStorge=='undefined'){
        this.init();
        window._localStorge = document.getElementById(this.id);
      }
      return window._localStorge;
    },
    setItem: function(key, value){
      var ietext = this.getInstance();
      ietxt.addBehavior("#default#userData");
      ietxt.setAttribute(key,value);
      return ietxt.save('SavedData');
    },
    getItem: function(key){
      var ietext = this.getInstance();
      ietxt.load('SavedData');
      return ietxt.getAttribute(key);
    },
    removeItem: function(key){
      var ietext = this.getInstance();
      return ietxt.removeAttribute(key);
    }
  }

  /**
   * 存放数据
   * @name set
   * @param {string} key 键名
   * @param {string} value 键值
   * @returns {string|null} obj 返回结果
   */
  exports.set = function(key,value){
    return localStorage.setItem(key,value);
  };

  /**
   * 读取数据
   * @name get
   * @returns {string|null} obj 返回结果
   */
  exports.get = function(key){
    return localStorage.getItem(key);
  };

  /**
   * 删除
   * @name remove
   * @returns {string|null} obj 返回结果
   */
  exports.remove = function(key){
    return localStorage.removeItem(key);
  };
});
