#scrapes
import json
from selenium import webdriver

# MOVIEe NAMES TO SCRAPE
lyrics = "knees are weak, arms heavy"
# flyrc = open('lyrics-list.json','r')
# lyrics = json.loads(flyrc.read())
#for line in flyrc:
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
lyrics_to_song = {}
f = open('lyrics_to_song.json', 'r')
jsonn = f.read()
lyrics_to_song = json.loads(jsonn)

# i = 0
# for lyrics in movies:
search_url = "http://www.lyricfind.com/services/lyrics-search/try-our-search/?q=%s" % ("+".join(lyrics.split(" ")))
wd.get(search_url)
new_link = wd.execute_script("return $($("h2")[3]).text().split("-");")
if new_link == None:
        skip.append(lyrics)
        continue
new_link = "http://www.soundtrackcollector.com" + new_link
wd.get(new_link)
songs = wd.execute_script("function getSongsOutOfMoviePage(){var res = []; $(\"b:contains('Track listing') ~ table:first td b\").each(function(i, v){ res.push($(v).html()); }); return res; }; return getSongsOutOfMoviePage();")
for s in songs:
        song_id = get_id(s)
        if song_id in lyrics_to_song:
                lyrics_to_song[song_id].append(lyrics)
        else:
                lyrics_to_song[song_id] = [lyrics]
if i % 100 == 0:
        f = open('lyrics_to_song.json', 'w')
        f.write(json.dumps(lyrics_to_song))
        # i += 1
f = open('lyrics_to_song.json', 'w')
f.write(json.dumps(lyrics_to_song))

print skip
