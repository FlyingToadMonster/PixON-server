# PixON-server
An on-line service to store every picture's meta data.

# PixON 服务器端
[PixON] 是一个在线服务，目的是让用户可以提交或取得任何图片的元信息。它的服务器端不存储任何图片内容，仅仅是以图片的 MD5 为索引存储一系列标准或自定义的字段。比如对于一张典型的图片，可能有如下记录：

    B637F78CE79AEEBAE74893A9CD4C64B3
    title: 中科大校徽
    description: 中国科学技术大学校徽 蓝色
    official: True
    full-size: False

这些记录可以由用户任意提交，并且随意下载（未来可能会加入限制，比如只有注册用户才能提交）。

[PixON]: http://0x01.me/PixON

## upload.php
这是提交元信息的API。应该以 GET 方法传入三个参数：`hash`、`key`、`value`。`hash`必须是32位十六进制数，`key`和`value`必须是UTF-8编码并以十六进制表示的字节串。例如，要提交上方例子中的“title”信息，应当发起这个GET请求：

    http://0x01.me/PixON/upload.php?hash=B637F78CE79AEEBAE74893A9CD4C64B3&key=7469746c65&value=e4b8ade7a791e5a4a7e6a0a1e5bebd

收到类似于如下回复：

    [version] 0.1.0
    [PixON] DONE.

返回的数据中应当包含一个`[PixON] `子串，接下来连着的四个大写字母指明了本次请求的结果，`FAIL`表示失败，`DONE`表示成功。这四个大写字母之后可能会有`: `，其后直至行末是附加的一些供人阅读的信息。

## download.php
这是获取元信息的API。应该以 GET 方法传入一个参数：`hash`。`hash`必须是32位十六进制数。例如，要获取上方例子中那张图片的元信息，应当发起这个GET请求：

    http://0x01.me/PixON/download.php?hash=B637F78CE79AEEBAE74893A9CD4C64B3

收到类似于如下回复：

    [version] 0.1.0
    [PixON] DATA.
    [DATA] NEXT.
    [DATA] key: 7469746c65
    [DATA] value: e4b8ade7a791e5a4a7e6a0a1e5bebd
    [DATA] votes: 0
    [DATA] DONE.

返回的数据中应当包含一个`[PixON] `子串，接下来连着的四个大写字母指明了本次请求的结果，`FAIL`表示失败，`DATA`表示成功，并且有额外的信息需要接收。这四个大写字母之后可能会有`: `，其后直至行末是附加的一些供人阅读的信息。

如果出现的是`[PixON] DATA`，接下来会有若干个`[DATA] `子串。`[DATA] NEXT`表示开始发送一条元信息记录。`[DATA] DONE`表示所有的记录已经发送完毕。`[DATA] key: `、`[DATA] value: `后面是UTF-8编码并以十六进制表示的字节串，分别为字段名和字段值。其他不认识的`[DATA] `串应当被忽略。

（话说……这个项目貌似不仅能用来存储图片元信息……其实可以为任何文件存储相关信息……）
