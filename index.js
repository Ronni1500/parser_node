var tress = require('tress');
var needle = require('needle');
var cheerio = require('cheerio');
var resolve = require('url').resolve;
var fs = require('fs');

var URL = 'https://vzpa.ru/Vreznoe-sedlo';
var results = [];

var q = tress(function(url, callback){
    needle.get(url, function(err, res){
        if (err) throw err;

        // парсим DOM
        var $ = cheerio.load(res.body);
        // for(let k = 0; k < 2; k++){
			let current_table = $('.table_production_for_clients').eq(0).find('tbody');
			let current_table_head = $('.table_production_for_clients').eq(0).find('thead');
			let count_l = [];
			current_table_head.find('tr th').each((i,e)=>{			
				if($(e).text().indexOf('ДУ 100') >= 0) count_l.push($(e).text());
			});	
			console.log(count_l);			
			current_table.find('tr').each((i,e)=>{
				for (let i = 0; i < count_l.length; i++) {	
					let price = parseInt($(e).find('td').eq(i+3).text().replace(/\r?\n\s*/g, "").replace(' ',''));
					results.push({
						name: 'Врезное седло ВЗПА',
						props: $(e).find('td').eq(1).text().replace('Врезное седло ','') 
							,
						price: (price) ? price : 0
					});
				}
			});		
        // }
	        callback();        	
    });
}, 10); // запускаем 10 параллельных потоков

q.drain = function(){
    fs.writeFileSync('./data.json', JSON.stringify(results, null, 4));
}

q.push(URL);