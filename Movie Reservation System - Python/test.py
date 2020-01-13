from flask import Flask, jsonify
from flask_cors import CORS
from flask import flash, redirect, render_template, request, session, abort
import json
import os

app = Flask(__name__)
CORS(app)

@app.route('/')
def home():
	if not session.get('logged_in'):
		return render_template('login.html')

@app.route('/login', methods=['POST'])
def do_admin_login():
	if checklogin(request.form['username'], request.form['password']):
		session['logged_in'] = True
		return render_template('showtimes.html')
	else:
		flash('wrong password!')
		return home()
		
@app.route("/logout")
def logout():
	session['logged_in'] = False
	return home()
		
@app.route('/readShowtimes')
def readShowtime():
	showtimes = []
	filehandle = open('showtimes.txt')
	showtimes = [show.rstrip() for show in filehandle.readlines()]
	return jsonify({'data':showtimes})

@app.route('/addShowtimes', methods=['POST'])
def addShowtimes():
	filehandle = open('showtimes.txt', 'w')
	showtimeData = json.loads(request.form['showtimeData'])
	print(showtimeData)
	filehandle.writelines("%s\n" % show for show in showtimeData)
	return ''

def checklogin(username, password):
	users = []
	usersMap = {}
	filehandle = open('login.txt')
	users = [user.rstrip() for user in filehandle.readlines()]
	for user in users:
		usersMap[user.split('#')[0]] = user.split('#')[1]
	if username in usersMap and usersMap[username] == password:
		return True
	else:
		return False

if __name__ == '__main__':
    app.secret_key = os.urandom(12)
    port = 8081 #the custom port you want
    app.run(host='0.0.0.0', port=port)