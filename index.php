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

  <script>
    const questions = [
      {
        type: "multiple",
        q: "Which method filters an array?",
        choices: ["map()", "filter()", "reduce()", "sort()"],
        answerIndex: 1
      },
      {
        type: "truefalse",
        q: "JavaScript is a compiled language.",
        answer: false
      },
      {
        type: "fill",
        q: "Fill in the blanks: To declare variables, you can use ____ or ____.",
        answer: ["let", "var"]
      },
{
        type: "fill",
        q: "Fill in the blanks: first name and last name ____ or ____.",
        answer: ["let", "var"]
      },
      {
        type: "multiple",
        q: "Which symbol is used for single-line comments?",
        choices: ["//", "/*", "#", "--"],
        answerIndex: 0
      },
      {
        type: "truefalse",
        q: "The DOM stands for Document Object Model.",
        answer: true
      }
    ];

    let shuffled = [];
    let currentIndex = 0;
    let score = 0;
    let timeLeft = 60;
    let timerInterval;
    let studentName = "";

    const startBtn = document.getElementById('startBtn');
    const qEl = document.getElementById('question');
    const cEl = document.getElementById('choices');
    const rEl = document.getElementById('result');
    const tEl = document.getElementById('timer');
    const quizEl = document.getElementById('quiz');
    const scoreList = document.getElementById('scoreList');
    const nameInput = document.getElementById('nameInput');
    const welcomeScreen = document.getElementById('welcomeScreen');

    function shuffle(array) {
      return array.slice().sort(() => Math.random() - 0.5);
    }

    function loadScoreboard() {
      const scores = JSON.parse(localStorage.getItem('quizScores') || '[]');
      scoreList.innerHTML = scores.map(s => `<li>${s.name} on ${s.date} — ${s.score}/${s.total}</li>`).join('');
    }

    function saveScore(score, total) {
      const scores = JSON.parse(localStorage.getItem('quizScores') || '[]');
      const now = new Date().toLocaleString();
      scores.unshift({ date: now, name: studentName, score, total });
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
          setTimeout(showQuestion, 2000); 
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
            setTimeout(showQuestion, 2000);
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
            setTimeout(showQuestion, 2000);
          };
          cEl.appendChild(btn);
        });
      } else if (current.type === "fill") {
        const input1 = document.createElement('input');
        input1.type = "text";
        input1.placeholder = "First answer...";
        cEl.appendChild(input1);
        
        const input2 = document.createElement('input');
        input2.type = "text";
        input2.placeholder = "Second answer...";
        cEl.appendChild(input2);

        const submitBtn = document.createElement('button');
        submitBtn.textContent = "Submit";
        submitBtn.onclick = () => {
          clearInterval(timerInterval);
          const userAnswer1 = input1.value.trim().toLowerCase();
          const userAnswer2 = input2.value.trim().toLowerCase();
          
          const correctAnswers = current.answer.map(a => a.toLowerCase());
          
          const isCorrect = correctAnswers.includes(userAnswer1) && 
                            correctAnswers.includes(userAnswer2) &&
                            userAnswer1 !== userAnswer2;
          
          if (isCorrect) {
            score++;
            rEl.textContent = "✅ Correct!";
          } else {
            rEl.textContent = `❌ Incorrect. Correct answers are: ${current.answer.join(" and ")}`;
          }
          currentIndex++;
          setTimeout(showQuestion, 2000);
        };
        cEl.appendChild(submitBtn);
      }
    }

    function endQuiz() {
      clearInterval(timerInterval);
      qEl.textContent = "🎉 Quiz Complete!";
      cEl.innerHTML = "";
      rEl.textContent = `Your score: ${score} / ${shuffled.length}`;
      tEl.style.display = 'none';
      saveScore(score, shuffled.length);
      loadScoreboard();
      startBtn.textContent = "🔁 Restart Quiz";
      startBtn.style.display = 'block';
    }

    loadScoreboard();
  </script>
</body>
</html>
