#scrapes
import json
from selenium import webdriver


wd = webdriver.Firefox()
movies = []
pages = 20
starting_page = "http://www.imdb.com/search/title?at=0&genres=action&sort=moviemeter,asc&start=01&title_type=feature"
next = starting_page

for i in range(pages):
	wd.get(next)
	more_movies = wd.execute_script("function getMoviesOutOfMovieResultsPage(){var res = []; jQuery(\"td.title>a\").each(function(i,v){ res.push(jQuery(v).html()); }); return res; }; return getMoviesOutOfMovieResultsPage();")
	for m in more_movies:
		movies.append(m)
	next = wd.execute_script("return $(\"#right a:last\").attr(\"href\")")
	next = "http://www.imdb.com" + next
	print(i)

f = open("movies-list.json", "w")
f.write(json.dumps(movies))
print("bingo")
