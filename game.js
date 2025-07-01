function Horse(id, x, y) {
    this.element = document.getElementById(id);
    this.speed = Math.random() * 5 + 7; // Random speed for each horse
    this.originX = x;
    this.originY = y;
    this.x = x;
    this.y = y;
    this.number = parseInt(id.replace(/[\D]/g, ''));
    this.lap = 0;
    this.wins = 0;  // Track wins for each horse

    this.moveRight = function() {
        var horse = this;
        const frameRate = 1000 / this.speed;
        const move = () => {
            let randomSpeed = Math.random() * 0.2 + 0.08;
            horse.x += randomSpeed;
            horse.element.style.left = horse.x + 'vw';

            if (horse.lap == num_lap && horse.x > horse.originX + 6) {
                horse.arrive();
            } else {
                if (horse.x < 82.5 - horse.number * 2.5) {
                    requestAnimationFrame(move);
                } else {
                    horse.element.className = 'horse runDown';
                    horse.speed = Math.random() * 5 + 7;
                    horse.moveDown();
                }
            }
        };
        setTimeout(move, frameRate);
    };

    this.moveDown = function() {
        var horse = this;
        const frameRate = 1000 / this.speed;
        const move = () => {
            let randomSpeed = Math.random() * 0.2 + 0.08;
            horse.y += randomSpeed;
            horse.element.style.top = horse.y + 'vh';

            if (horse.y < horse.originY + 65) {
                requestAnimationFrame(move);
            } else {
                horse.element.className = 'horse runLeft';
                horse.speed = Math.random() * 5 + 7;
                horse.moveLeft();
            }
        };
        setTimeout(move, frameRate);
    };

    this.moveLeft = function() {
        var horse = this;
        const frameRate = 1000 / this.speed;
        const move = () => {
            let randomSpeed = Math.random() * 0.2 + 0.08;
            horse.x -= randomSpeed;
            horse.element.style.left = horse.x + 'vw';

            if (horse.x > 12.5 - horse.number * 2.5) {
                requestAnimationFrame(move);
            } else {
                horse.element.className = 'horse runUp';
                horse.speed = Math.random() * 5 + 7;
                horse.moveUp();
            }
        };
        setTimeout(move, frameRate);
    };

    this.moveUp = function() {
        var horse = this;
        const frameRate = 1000 / this.speed;
        const move = () => {
            let randomSpeed = Math.random() * 0.2 + 0.08;
            horse.y -= randomSpeed;
            horse.element.style.top = horse.y + 'vh';

            if (horse.y > horse.originY) {
                requestAnimationFrame(move);
            } else {
                horse.element.className = 'horse runRight';
                horse.lap++;
                horse.moveRight();
            }
        };
        setTimeout(move, frameRate);
    };

    this.run = function() {
        this.element.className = 'horse runRight';
        this.moveRight();
    };

    this.arrive = function() {
        this.element.className = 'horse standRight';
        this.lap = 0;

        var tds = document.querySelectorAll('#results .result');
        tds[results.length].className = 'result horse' + this.number;
        results.push(this.number);

        if (results.length == 1) {
            raceWin.play();

            if (this.number == bethorse) {
                funds += amount * 2; // Double the bet amount if the player wins
                document.getElementById('walletBalance').innerText = `₹${funds}`;
            }

            // Update the table with the rankings after each finish
            updateRankingTable();

            // Increment the win count for this horse
            this.wins++;

            // If a horse wins 2 times, disable further wins for this horse
            if (this.wins >= 2) {
                this.element.classList.add('noWin'); // You can define a CSS class for this
                alert(`Horse ${this.number} has won 2 times and cannot win again!`);
            }
        }

        if (results.length == 4) {
            document.getElementById('start').disabled = false;
        }
    };
}

// Update ranking table
function updateRankingTable() {
    const table = document.getElementById('liveRanking');
    const row = table.insertRow();
    const cell1 = row.insertCell(0);
    const cell2 = row.insertCell(1);

    cell1.textContent = results.length; // Rank number
    cell2.textContent = `Horse ${results[results.length - 1]}`; // Horse number
}

var num_lap = 1, results = [], funds = 100, bethorse, amount;

document.addEventListener("DOMContentLoaded", function(event) {
    var horse1 = new Horse('horse1', 20, 4);
    var horse2 = new Horse('horse2', 20, 8);
    var horse3 = new Horse('horse3', 20, 12);
    var horse4 = new Horse('horse4', 20, 16);

    var raceMusic = new Audio('media/zelda_horse_race.mp3');
    var raceStart = new Audio('media/race-start.mp3');
    var raceWin = new Audio('media/oot_horse_race_win.mp3');
    var raceHorses = new Audio('media/horse_run_and_neh.mp3');
    var horseSound = new Audio('media/horse_sound.mp3');

    fetch('get_balance.php', {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error('Error fetching balance:', data.error);
        } else {
            funds = data.balance;
            document.getElementById('walletBalance').innerText = `₹${funds}`;
        }
    })
    .catch(error => console.error('Fetch error:', error));

    document.getElementById('start').onclick = function() {
        amount = parseFloat(document.getElementById('amount').value);
        num_lap = parseInt(document.getElementById('num_lap').value);
        bethorse = parseInt(document.getElementById('bethorse').value);

        if (amount <= 0 || isNaN(amount)) {
            alert('Please enter a valid bet amount.');
            return;
        }

        if (funds < amount) {
            alert('Not enough funds.');
            return;
        }

        if (num_lap <= 0) {
            alert('Number of laps must be greater than 0.');
            return;
        }

        this.disabled = true;
        let tds = document.querySelectorAll('#results .result');
        for (let i = 0; i < tds.length; i++) {
            tds[i].className = 'result';
        }

        funds -= amount;
        document.getElementById('walletBalance').innerText = `₹${funds}`;

        results = [];
        raceStart.play();
        raceMusic.play();
        horseSound.play();
        raceHorses.play();

        horse1.run();
        horse2.run();
        horse3.run();
        horse4.run();

        fetch('record_bet.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'horse_id': bethorse,
                'bet_amount': amount,
                'num_lap': num_lap
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error recording bet:', data.error);
            } else {
                funds = data.new_balance;
                document.getElementById('walletBalance').innerText = `₹${funds}`;
                console.log('Bet recorded and balance updated:', data);
            }
        })
        .catch(error => console.error('Fetch error:', error));
    };
});
