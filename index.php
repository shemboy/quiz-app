<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Mixed-Type Timed Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      color: #333;
      margin: 0;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    h1 {
      text-align: center;
      color: #2c3e50;
    }
    #welcomeScreen, #quiz, #timer, #scoreboard {
      width: 100%;
      max-width: 600px;
      margin-top: 1rem;
    }
    #quiz, #timer {
      display: none;
    }
    button {
      display: block;
      width: 100%;
      padding: 0.75rem;
      margin: 0.5rem 0;
      font-size: 1rem;
      background: #3498db;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #2980b9;
    }
    input[type="text"] {
      width: 100%;
      padding: 0.6rem;
      font-size: 1rem;
      margin: 0.5rem 0;
      box-sizing: border-box;
    }
    .timer, .result, .scoreboard h2 {
      font-weight: bold;
      margin-top: 1rem;
    }
    ul {
      padding-left: 1rem;
    }
    @media (max-width: 480px) {
      button, input[type="text"] {
        font-size: 0.95rem;
        padding: 0.6rem;
      }
      h1 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <h1>🧠 Quiz</h1>

  <div id="welcomeScreen">
    <p>Please enter your name to start the quiz:</p>
    <input type="text" id="nameInput" placeholder="Enter your name" />
    <button id="startBtn">▶️ Start Quiz</button>
  </div>
  
  <div class="timer" id="timer">Time left: 60s</div>
  <div id="quiz">
    <p id="question"></p>
    <div id="choices"></div>
    <div id="result" class="result"></div>
  </div>
  
  <div class="scoreboard" id="scoreboard">
    <h2>📋 Scoreboard</h2>
    <ul id="scoreList"></ul>
  </div>




//Please edit the const questions array and add your quiz questions here. Test the code thoroughly before sending it to Mr. Dualos.

  <script>

    const questions = [
      {
        type: "multiple",
        q: "1. What was the state of the firewall in Windows XP when it was released in 2001?",
        choices: [
          "A. Advanced firewall enabled by default ",
          "B. No firewall included ",
          "C. Basic firewall disabled by default",
          "D. Firewall with real-time protection"
        ],
        answerIndex: 2
      },




      {
        type: "fill",
        q: "Fill in the blank: The keyword to declare a constant is ____.",
        answer: "const"
      },








      {
        type: "truefalse",
        q: "18. TPM 2.0 is a requirement for Windows 11 security features.",
        answer: true
      }
    ];

    let shuffled = [];
    let currentIndex = 0;
    let score = 0;
    let timeLeft = 60;
    let timerInterval;
    let studentName = "";
    let quizStartTime;

    const startBtn = document.getElementById('startBtn');
    const qEl = document.getElementById('question');
    const cEl = document.getElementById('choices');
    const rEl = document.getElementById('result');
    const tEl = document.getElementById('timer');
    const quizEl = document.getElementById('quiz');
    const scoreList = document.getElementById('scoreList');
    const nameInput = document.getElementById('nameInput');
    const welcomeScreen = document.getElementById('welcomeScreen');
    const scoreboard = document.getElementById('scoreboard');

    function shuffle(array) {
      return array.slice().sort(() => Math.random() - 0.5);
    }

    function loadScoreboard() {
      const scores = JSON.parse(localStorage.getItem('quizScores') || '[]');
      scoreList.innerHTML = scores.map(s => `<li>${s.name} on ${s.date} — ${s.score}/${s.total} - Time: ${s.time}</li>`).join('');
    }

    function saveScore(score, total, time) {
      const scores = JSON.parse(localStorage.getItem('quizScores') || '[]');
      const now = new Date().toLocaleString();
      scores.unshift({ date: now, name: studentName, score, total, time });
      localStorage.setItem('quizScores', JSON.stringify(scores.slice(0, 10)));
    }

    startBtn.addEventListener('click', () => {
      const name = nameInput.value.trim();
      if (name === "") {
        alert("Please enter your name to start.");
        return;
      }
      studentName = name;
      welcomeScreen.style.display = 'none';
      quizEl.style.display = 'block';
      tEl.style.display = 'block';
      shuffled = shuffle(questions);
      currentIndex = 0;
      score = 0;
      quizStartTime = new Date();
      showQuestion();
    });

    function showQuestion() {
      if (currentIndex >= shuffled.length) {
        endQuiz();
        return;
      }

      timeLeft = 60;
      tEl.textContent = `Time left: ${timeLeft}s`;
      clearInterval(timerInterval);
      timerInterval = setInterval(() => {
        timeLeft--;
        tEl.textContent = `Time left: ${timeLeft}s`;
        if (timeLeft <= 0) {
          clearInterval(timerInterval);
          rEl.textContent = "⏱️ Time's up!";
          currentIndex++;
          setTimeout(showQuestion, 1000);
        }
      }, 1000);

      const current = shuffled[currentIndex];
      qEl.textContent = current.q;
      cEl.innerHTML = "";
      rEl.textContent = "";

      if (current.type === "multiple") {
        current.choices.forEach((choice, idx) => {
          const btn = document.createElement('button');
          btn.textContent = choice;
          btn.onclick = () => {
            clearInterval(timerInterval);
            if (idx === current.answerIndex) {
              score++;
              rEl.textContent = "✅ Correct!";
            } else {
              rEl.textContent = `❌ Incorrect. Correct: ${current.choices[current.answerIndex]}`;
            }
            currentIndex++;
            setTimeout(showQuestion, 1000);
          };
          cEl.appendChild(btn);
        });
      } else if (current.type === "truefalse") {
        ["True", "False"].forEach((val) => {
          const btn = document.createElement('button');
          btn.textContent = val;
          btn.onclick = () => {
            clearInterval(timerInterval);
            const userAnswer = val === "True";
            if (userAnswer === current.answer) {
              score++;
              rEl.textContent = "✅ Correct!";
            } else {
              rEl.textContent = `❌ Incorrect. Correct: ${current.answer ? "True" : "False"}`;
            }
            currentIndex++;
            setTimeout(showQuestion, 1000);
          };
          cEl.appendChild(btn);
        });
      } else if (current.type === "fill") {
        const input = document.createElement('input');
        input.type = "text";
        input.placeholder = "Type your answer...";
        cEl.appendChild(input);

        const submitBtn = document.createElement('button');
        submitBtn.textContent = "Submit";
        submitBtn.onclick = () => {
          clearInterval(timerInterval);
          const userAnswer = input.value.trim().toLowerCase();
          const correctAnswer = current.answer.toLowerCase();
          if (userAnswer === correctAnswer) {
            score++;
            rEl.textContent = "✅ Correct!";
          } else {
            rEl.textContent = `❌ Incorrect. Correct: ${current.answer}`;
          }
          currentIndex++;
          setTimeout(showQuestion, 1000);
        };
        cEl.appendChild(submitBtn);
      }
    }

    function endQuiz() {
      clearInterval(timerInterval);
      
      const quizEndTime = new Date();
      const totalTimeInMs = quizEndTime - quizStartTime;
      const totalSeconds = Math.floor(totalTimeInMs / 1000);
      const minutes = Math.floor(totalSeconds / 60);
      const seconds = totalSeconds % 60;
      const formattedTime = `${minutes}m ${seconds}s`;

      qEl.textContent = "🎉 Quiz Complete!";
      cEl.innerHTML = "";
      rEl.innerHTML = `Your score: ${score} / ${shuffled.length}<br>Total time: ${formattedTime}`;
      tEl.style.display = 'none';
      saveScore(score, shuffled.length, formattedTime);
      loadScoreboard();
    }

    loadScoreboard();
  </script>
</body>
</html>
