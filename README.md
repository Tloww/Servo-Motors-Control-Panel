# Servo Motors Control Panel

## Project Idea
This project provides a web-based control panel to manage Servo Motors using an interactive interface.  
Different motor angles can be saved into a MySQL database, loaded later, or directly sent to the ESP microcontroller.

---

## Features Implemented
- UI Design with HTML + CSS + PHP (dark themed control panel).  
- Four sliders to control servo motor angles.  
- Reset to 90° button to reset all motors at once.  
- Save Position button to store motor angles into the database.  
- Load button to retrieve and apply saved positions.  
- Submit to ESP button to send the currently loaded or adjusted angles to the ESP.  
- Angles.php page returns the latest sent angles in CSV format for the ESP to read.  
- Success/error messages styled consistently (green success box, etc.).  

---

## Screenshots
### Control Panel
![Control Panel](./Screenshot%202025-10-25%20003344.png)

### Success Message
![Success Message](./Screenshot%202025-10-25%20003416.png)

### Angles Output
![Angles Output](./Screenshot%202025-10-25%20003426.png)

---

## Main Files
- index.php → Main web interface with sliders and action buttons.  
- Angles.php → Outputs the latest angles submitted to the ESP.  
