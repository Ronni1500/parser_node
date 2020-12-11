var tress = require('tress');
var needle = require('needle');
var cheerio = require('cheerio');
var resolve = require('url').resolve;
var fs = require('fs');
var http = require('http');

var domain = 'http://at.hoermannpartner.ru';
var bitrixShowAll = '?SHOWALL_1=1';

var URL = domain + '/product/vezdnye-otkatnye-vorota/';

var results = [];

var tmpResult = [];

var prefix = 'funktsionalnye-dveri-dlya-stroitelstva-obektov';


var qSection = tress(function(url, callback){
    needle.get(url, function(err, res){
        if (err) throw err;

        // парсим DOM
        var $ = cheerio.load(res.body);
        // Выкачать все картиник с сайта
        // console.log('results');
        // $('.s-content img').each(function(i,e) {
        //     var img = $(e).attr('src');
        //     var imgName = img.split('/');
        //     imgName = imgName[imgName.length - 1];            

        //     var file = fs.createWriteStream('img/'+imgName);   
        //     var request = http.get(domain + img, function(response) {
        //       response.pipe(file);
        //     });         
         
        // });

        // $('#news-in-catalog .item-of-list').each(function(i, e) {
        // $('.s-content .s-type.section .s-type__col').each(function(i, e) {
        $('.s-content__slider-wrap-var-2 .s-content__slide').each(function(i, e) {
            var pdf = $(this).find('a.s-content__slide-img-wrap').attr('href');
            var pdfName = pdf.split('/');
            pdfName = pdfName[pdfName.length - 1];
            pdfName = pdf.split('.');
            pdfName = pdfName[pdfName.length - 1];


            var file = fs.createWriteStream('img/' + $(this).index() + '.' + pdfName);
            var request = http.get(domain + pdf, function(response) {
              response.pipe(file);
            });


            var img = $(this).find('img').attr('data-lazy');            
            var imgName = img.split('/');
            imgName = imgName[imgName.length - 1];
            imgName = imgName.split('.');
            imgName = imgName[imgName.length - 1];


            var fileImg = fs.createWriteStream('img/' + $(this).index() + '.' + imgName);
            // console.log('<div class="col-xs-12 col-md-3 catalog-detail-list__item"><img src="/images/catalog/hormann/' + prefix + '/' + imgName + '"><span class="catalog-detail-list__name">' + name + '</span></div>');            
            var requestImg = http.get(domain + img, function(response) {
              response.pipe(fileImg);
            });
        });
    callback();
    });    
},10);// запускаем 10 параллельных потоков

console.log('<div class="catalog-detail-list row">');
 qSection.push(URL);





