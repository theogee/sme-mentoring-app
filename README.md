# SME (Small-to-Medium Enterprise) MENTORING APPLICATION
University (SEM 2) final project building web application to help SMEs grow their business by establishing mentoring program with field experts and providing connections to company who wants to conduct their CSR program. 

## TO RUN
Requirements:
- XAMPP installed
- Composer installed
- Apache HTTP server running
- MySQL running with imported DB: ```mysql/sme-mentoring-app.sql```

  
Note:<br />
By the time of making, the past _me_ didn't use any ```env``` variable in the program, thus you'll need to make adjustment directly inside ```utility/dbconn.php``` to match your environment 😜

Step-by-step:
1. Move the project folder to ```htdocs/``` folder under your XAMPP installation folder.
2. Run the chat server: 
    - from the root project folder, navigate to ```ws/``` and run ```composer update``` to install Ratchet (WebSocket library for PHP)
    - in the same directory, run ```php bin/server.php``` to run the chat server
3. If all requirements have been set up correctly, you should be able to access the app at ```http://localhost/sme-mentoring-app```

## SCREENSHOT EXAMPLE
<img alt="business owner dashboard" src="https://user-images.githubusercontent.com/85065433/175504914-7798408a-68c7-4d7c-93cc-193bae8455e6.PNG" />

