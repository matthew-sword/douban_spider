爬虫的原理是从一个起始种子链接开始，发http请求这个链接，得到该链接中的内容，
然后大多使用正则匹配出页面里面的有效链接，
然后将这些链接保存到待访问队列中，等待爬取线程取这个待访队列，
一旦链接访问过了，为了有效的减少不必要的网络请求，我们应该把访问过的链接放到一个已访问map中，已防止重复抓取及死循环。
我以上提到的过程可能是一个比较简单的爬虫实现，复杂的可能不会这么简单，
但这里面有几个概念，
一个是发http请求，
一个是正则匹配你感兴趣的链接，
一个是多线程，
另外还有两个队列，


爬虫学习--完成一个豆瓣电影的爬虫

要求：

网络请求使用原生自带的接口，不得使用第三方

了解Web反爬虫原理，至少要有3个方法防止豆瓣反爬虫

网页解析部分，首选运用正则表达式来提取内容，后可使用第三方框架：BeautifulSoup(Python),simple_html_dom(PHP)

支持搜索电影，支持查看电影影评、海报，支持查看Top250的电影目录以及详细信息。

将豆瓣电影条目封装成类，如下：
class Movie(object):

    def __init__():

    def getName():

    def getID():

    def getMovieImage():

    def getComments():

    def get....








HTTP请求->curl（抓取并下载html页面文件）
		      top250url->跳转详情页->获取数据
              拼接搜索url（get方法）->跳转详情页->获取数据
		





网页内容匹配->simple_html_dom（从文件中加载html）

anti-anti-spider策略
    更换ip，client
	有规律的sleep（php sleep函数）
	//使用webkit提交数据



查找海报
https://img1.doubanio.com/view/movie_poster_cover/lpst/public/p2492665487.jpg  //海报url格式

top250
https://movie.douban.com/top250?start=75&filter=        //top250翻页 start=75在第四页    75=25*3
