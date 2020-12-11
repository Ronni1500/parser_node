var tress = require('tress');
var needle = require('needle');
var cheerio = require('cheerio');
var resolve = require('url').resolve;
var fs = require('fs');

var domain = 'https://doorhan.ru';
var bitrixShowAll = '?SHOWALL_1=1';

var URL = 'https://doorhan.ru/catalog/zapasnye-chasti-dlya-avtomatiki-doorhan/';

var results = [];

var tmpResult = [];

var q = ( function(url, callback){
    needle.get(url, function(err, res){
        if (err) throw err;

        // парсим DOM
        var $ = cheerio.load(res.body);
        tmpResult = [];
        $('.title_detail').each(function(i, e) {
            var name = $(e).find('.main-title__text').text().trim();
            var contentBlock = $(e).next('.mini-container');
            var img = domain + contentBlock.find('.left_part_catalog img').attr('src');
            
            var charact = [];
            contentBlock.find('.up_table').eq(0).find('tr').each(function(index, element) {             
                var name = $(element).find('td').eq(0).text().trim();
                var value = $(element).find('td').eq(1).text().trim();
                charact.push({
                    name,
                    value
                });
            });         
            var articul = [];
            contentBlock.find('.up_table').eq(1).find('tr').each((iArt, eArt)  => {
                if($(eArt).find('th').length > 0) return true;  
                if($(eArt).find('td').length == 1){
                    var name = $(eArt).find('td').eq(0).text().trim(); 
                    var value = '';   
                } else {
                    var name = $(eArt).find('td').eq(1).text().trim();
                    var value = $(eArt).find('td').eq(0).text().trim();                    
                }
                articul.push({
                    name,
                    value
                });

            });
            var desc = contentBlock.find('.catalog-item-description').text();
            tmpResult.push({
                name,
                img,
                charact,
                articul,
                desc

            });    
              // console.log('asd');      
        });
        callback();
    });
}); 



var qSection = tress(function(url, callback){
    needle.get(url, function(err, res){
        if (err) throw err;

        // парсим DOM
        var $ = cheerio.load(res.body);
            // console.log('results');
        $('.partner_block_inside').each(function(i, e) {
            var title = $(e).find('.partner_bottom_title_text').text().trim();
            var img = domain + $(e).find('.image').find('img').attr('src');
            var link = $(e).find('.image').find('a').attr('href');

            // парсим внутрение карточки
            q(domain + link + bitrixShowAll, function(){
                results.push({
                    title,
                    img,  
                    link,              
                    items: tmpResult
                }); 
                console.log("Страница", domain + link + bitrixShowAll); 
                fs.writeFileSync('./data.json', JSON.stringify(results, null, 4)); 
            });            
            
        });
    callback();
    });
},10);// запускаем 10 параллельных потоков

 qSection.push(URL);





