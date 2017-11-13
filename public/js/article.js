var E = window.wangEditor;
var editor = new E('#div1','#div2');
editor.customConfig.uploadImgServer = '/upload'  // 上传图片到服务器
editor.customConfig.linkImgCallback = function (url) {
    console.log(url) // url 即插入图片的地址
}
//TODO 配置debug模式 记得结束之后删除
editor.customConfig.debug = true

editor.customConfig.onchange = function (html) {
    // html 即变化之后的内容
    console.log(html)
}
// 自定义 onchange 触发的延迟时间，默认为 200 ms
editor.customConfig.onchangeTimeout = 1000 // 单位 ms

editor.customConfig.linkCheck = function (text, link) {
    console.log(text) // 插入的文字ds
    console.log(link) // 插入的链接
    return true // 返回 true 表示校验成功
    // return '验证失败' // 返回字符串，即校验失败的提示信息
}

editor.customConfig.uploadImgServer = '/api/article/img/upload';
editor.customConfig.uploadFileName = 'myFileName';
// 将图片大小限制为 5M
editor.customConfig.uploadImgMaxSize = 5 * 1024 * 1024
// 限制一次最多上传 15 张图片
editor.customConfig.uploadImgMaxLength = 15
editor.customConfig.uploadImgHooks = {
    before: function (xhr, editor, files) {
        // 图片上传之前触发
        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，files 是选择的图片文件
        // 如果返回的结果是 {prevent: true, msg: 'xxxx'} 则表示用户放弃上传
        // return {
        //     prevent: true,
        //     msg: '放弃上传'
        // }
    },
    success: function (xhr, editor, result) {
        // 图片上传并返回结果，图片插入成功之后触发
        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
    },
    fail: function (xhr, editor, result) {
        // 图片上传并返回结果，但图片插入错误时触发
        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，result 是服务器端返回的结果
    },
    error: function (xhr, editor) {
        // 图片上传出错时触发
        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
    },
    timeout: function (xhr, editor) {
        // 图片上传超时时触发
        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象
    },

    // 如果服务器端返回的不是 {errno:0, data: [...]} 这种格式，可使用该配置
    // （但是，服务器端返回的必须是一个 JSON 格式字符串！！！否则会报错）
    customInsert: function (insertImg, result, editor) {
        // 图片上传并返回结果，自定义插入图片的事件（而不是编辑器自动插入图片！！！）
        // insertImg 是插入图片的函数，editor 是编辑器对象，result 是服务器端返回的结果
        // 举例：假如上传图片成功后，服务器端返回的是 {url:'....'} 这种格式，即可这样插入图片：
        var data = result.data
        insertImg(data)
        // result 必须是一个 JSON 格式字符串！！！否则报错
    }
}
editor.create();
