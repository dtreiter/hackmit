#scrapes
import json
from selenium import webdriver

# MOVIE NAMES TO SCRAPE
movies = []
mov = open('movies-list.json','r')
movies = json.loads(mov.read())
#for line in mov:
#        movies.append(line)

skip = []

def get_id(query):
    search_url = "http://ws.spotify.com/search/1/track.json?q=%s" % ("+".join(query.split(" ")))
    wd.get(search_url)
    try:
            jdict = json.loads(wd.find_element_by_tag_name("pre").text)
            return jdict['tracks'][0]['href']
    except:
            print search_url


'''
["Schindler's List\n", "One Flew Over the Cuckoo's Nest\n", "It's a Wonderful Life\n", 'L\xc3\xa9on: The Professional\n', 'M\n', "Singin' in the Rain\n", "Pan's Labyrinth\n", 'Up\n', "The King's Speech\n", "Howl's Moving Castle\n", "Who's Afraid of Virginia Woolf?\n", '8\xc2\xbd\n', 'Fanny and Alexander\n', 'Incendies\n', "Rosemary's Baby\n", 'Like Stars on Earth\n', 'Nausica\xc3\xa4 of the Valley of the Wind\n']
'''

wd = webdriver.Firefox()
song_to_movies = {}
f = open('song-to-mov.json', 'r')
jsonn = f.read()
song_to_movies = json.loads(jsonn)
i = 0
for movie in movies:
        search_url = "http://www.soundtrackcollector.com/catalog/search.php?searchon=title&searchtext=%s" % ("+".join(movie.split(" ")))
        wd.get(search_url)
        new_link = wd.execute_script("return jQuery(\"a[href^='/title']:nth(1)\").attr('href');")
        if new_link == None:
                skip.append(movie)
                continue
        new_link = "http://www.soundtrackcollector.com" + new_link
        wd.get(new_link)
        songs = wd.execute_script("function getSongsOutOfMoviePage(){var res = []; $(\"b:contains('Track listing') ~ table:first td b\").each(function(i, v){ res.push($(v).html()); }); return res; }; return getSongsOutOfMoviePage();")
        for s in songs:
                song_id = get_id(s)
                if song_id in song_to_movies:
                        song_to_movies[song_id].append(movie)
                else:
                        song_to_movies[song_id] = [movie]
        if i % 100 == 0:
                f = open('song-to-mov.json', 'w')
                f.write(json.dumps(song_to_movies))
        i += 1
f = open('song-to-mov.json', 'w')
f.write(json.dumps(song_to_movies))

print skip
