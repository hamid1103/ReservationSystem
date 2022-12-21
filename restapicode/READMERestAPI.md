# ReservationSystem -> Rest Api
This folder contains the code which runs on a seperate nodeJS server.  
It handles all outlook(ms graph) related shit since that is such horseshit to work with in php. Also because that will make it
easier and more secure to work with, since it will directly handle stuff on client's account using the ms graph api. 
  
Dependends on:
Express, Cors, Lodash, Uuid, MS graph

Notes:  
Create .env file and load api keys and secrets from there