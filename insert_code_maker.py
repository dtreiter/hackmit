import json
f = open("song-to-mov.json", "r")
d = json.loads(f.read())
code = ""
for sid in d:
	for i in range(len(d[sid])):
		d[sid][i] = d[sid][i].encode("utf-8")
	code += ("$client->putItem(array('TableName' => 'songs', 'Item' => array('song_id' => array(Type::STRING => '%s') , 'movies' => array(Type::STRING => '%s') ) ));\n" % (sid, str(d[sid])))
f = open("insert.php", "w")
f.write(code)
f.close()
