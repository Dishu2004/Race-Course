<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Race Course Betting</title>
    <style>

        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #fff;
}

/* Header */
.header {
    background-color: #0a1931;
    text-align: center;
    padding: 20px;
    font-size: 24px;
    font-weight: bold;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: center;
    background-color: #1b2a49;
    padding: 15px;
}

.navbar a {
    color: #ffffff;
    text-decoration: none;
    padding: 10px 20px;
    font-size: 18px;
    transition: 0.3s;
}

.navbar a:hover {
    background-color: #3a4d6d;
    border-radius: 5px;
}

/* Hero Section */
.hero {
    background-image: url('hhh.jpg');
    background-size: cover;
    background-position: center;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.hero-text {
    background: rgba(0, 0, 0, 0.6);
    padding: 20px;
    border-radius: 10px;
    font-size: 32px;
    font-weight: bold;
}

/* Section Styles */
.section {
    padding: 50px;
    text-align: center;
    background-color: #0a1931;
}

.section h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #ffcc00;
}

/* Card Container */
.card-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

/* Cards */
.card {
    background-color: #1b2a49;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    text-align: center;
    width: 250px;
    transition: transform 0.3s ease-in-out;
}

.card img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}

.card h3 {
    font-size: 20px;
    color: #ffcc00;
}

.card:hover {
    transform: scale(1.05);
}

/* Footer */
.footer {
    background-color: #1b2a49;
    text-align: center;
    padding: 20px;
    font-size: 16px;
    color: #ffcc00;
}

button
{
 background-color:  #0a1931;
 height :50px;
 width : 100px;
 color: #ffcc00;
 border-radius: 15px;
}
button:hover {
    background-color: #3a4d6d;
    border-radius: 5px;
}


    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        Race Course
    </header>

    <!-- Navbar -->
    <nav class="navbar">
        <a href= "#hero">Login/Signup</a>
        <a href="#about">About Us</a>
        <a href="#horses">Horses</a>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="hero">
        <div class="hero-text">Welcome to the Ultimate Race Course Betting<br><br>
	Start your Juorney with <a href="login.php"><button>Login/Signup</button></a></div>
    </section>


    <!-- About Us Section -->
    <section id="about" class="section">
        <h2>About Us</h2>
        <div class="card-container">
            <div class="card">
                <img src="dishank.jpg" alt="Developer 1">
                <h3>Dishank.D.Mali</h3>
            </div>
            <div class="card">
                <img src="snc.jpg" alt="Developer 2">
                <h3>Sanchit Nagaonkar</h3>
            </div>
            <div class="card">
                <img src="ak.jpg" alt="Developer 3">
                <h3>Akash Appa Bhagwat </h3>
            </div>
        </div>
    </section>

    <!-- Horses Section -->
    <section id="horses" class="section">
        <h2>Horses</h2>
        <div class="card-container">
            <div class="card">
                <img src="wh2.jpg" alt="Thunderbolt">
                <h3>White</h3>
            </div>
            <div class="card">
                <img src="bh.jpeg" alt="Shadow">
                <h3>Brown</h3>
            </div>
            <div class="card">
                <img src="bhh.jpeg" alt="Blaze">
                <h3>Blue</h3>
            </div>
            <div class="card">
                <img src="gh.png" alt="Storm">
                <h3>Green</h3>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>Project Race Course BG20</p>
        <p>&copy; 2025 Race Course Betting. All rights reserved.</p>
    </footer>

    <script>// Smooth scrolling for navigation links
        document.querySelectorAll('.navbar a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
        
                if (targetSection) {
                    window.scrollTo({
                        top: targetSection.offsetTop - 60,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Highlight active section in navbar
        window.addEventListener('scroll', () => {
            let fromTop = window.scrollY + 80;
            
            document.querySelectorAll('.navbar a').forEach(link => {
                let section = document.getElementById(link.getAttribute('href').substring(1));
        
                if (section && section.offsetTop <= fromTop && section.offsetTop + section.offsetHeight > fromTop) {
                    link.style.color = "#ffcc00"; // Highlight active link
                } else {
                    link.style.color = "#ffffff"; // Reset color
                }
            });
        });
        
        // Hover effect on cards (Scale effect)
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = "scale(1.1)";
                card.style.transition = "0.3s ease-in-out";
            });
        
            card.addEventListener('mouseleave', () => {
                card.style.transform = "scale(1)";
            });
        });
        </script>
</body>
</html>
