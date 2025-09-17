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
      type: "multiple",
     q: "2. Why did users of Windows XP (2001) need third-party antivirus software?",
     choices: [
        "A. Windows XP lacked built-in antivirus protection",
        "B. Windows XP was incompatible with antivirus software ",
        "C. Windows XP had built-in antivirus ",
        "D. Windows XP blocked third-party antivirus tools"
      ],
      answerIndex: 0
    },


    {
      type: "multiple",
     q: "3. Which security feature was introduced in Windows XP Service Pack 2 (2004)? ",
     choices: [
        "A. Security Center",
        "B. BitLocker ",
        "C. User Account Control (UAC) ",
        "D. Windows Defender"
      ],
      answerIndex: 0
    },



    {
      type: "multiple",
     q: "4. What was the purpose of the Security Center introduced in Windows XP SP2? ",
     choices: [
        "A. To install third-party antivirus software ",
        "B. To manage user accounts ",
        "C. To monitor and manage system security settings",
        "D. To disable the firewallP"
      ],
      answerIndex: 2
    },



    {
      type: "multiple",
     q: "5. Which version of Windows introduced User Account Control (UAC)? ",
     choices: [
        "A. Windows 7 ",
        "B. Windows XP ",
        "C. Windows Vistas",
        "D. Windows XP SP2"
      ],
      answerIndex: 3
    },


    {
      type: "multiple",
     q: "6. What does User Account Control (UAC) do? ",
     choices: [
        "A. Prompts users before system changes",
        "B. Disables antivirus software ",
        "C. Automatically installs updates ",
        "D. Blocks internet access"
      ],
      answerIndex: 0
    },



    {
      type: "multiple",
     q: "7. Which anti-spyware tool was included by default in Windows Vista? ",
     choices: [
        "A. McAfee Security ",
        "B. Windows Defender",
        "C. Norton Antivirus ",
        "D. Malwarebytes"
      ],
      answerIndex: 1
    },


    {
      type: "multiple",
     q: "8. How did Windows Vista improve security compared to previous versions? ",
     choices: [
        "A. By disabling antivirus software  ",
        "B. By introducing UAC and including Windows Defender",
        "C. By removing the firewall ",
        "D. By allowing unrestricted system changes"
      ],
      answerIndex: 1
    
    },













      {
        type: "truefalse",
        q: "9. Windows XP’s firewall was enabled by default in 2001.",
        answer: false
      },

      {
        type: "truefalse",
        q: "10. Windows Vista introduced User Account Control (UAC).",
        answer: true
      },

      {
        type: "truefalse",
        q: "11. SmartScreen Filter only works on websites and cannot scan apps.",
        answer: false
      },


      {
        type: "truefalse",
        q: "12. Windows Hello is faster and safer than traditional password logins.",
        answer: true
      },


      {
        type: "truefalse",
        q: "13. BitLocker is available in all Windows editions including Home.",
        answer: false
      },

      {
        type: "truefalse",
        q: "14. Ransomware locks files and demands payment for unlocking them.",
        answer: true
      },


      {
        type: "truefalse",
        q: "15. Microsoft Defender Antivirus in Windows 7 already had full antivirus capability.",
        answer: false
      },


      {
        type: "truefalse",
        q: "16. Controlled Folder Access was introduced as an update to Windows 10.",
        answer: true
      },

      {
        type: "truefalse",
        q: "17.Windows Security includes both account and identity protection",
        answer: true
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
          // Changed back to 1000ms (1 second)
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
            // Changed back to 1000ms (1 second)
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
            // Changed back to 1000ms (1 second)
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
          // Changed back to 1000ms (1 second)
          setTimeout(showQuestion, 1000);
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
