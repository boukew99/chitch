<?php

if (php_sapi_name() !== 'cli-server') {
    exit(); // Ensure this script only runs in CLI server mode.
}

chdir(dirname($_SERVER['DOCUMENT_ROOT']));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['command'])) {
    while (ob_get_level() > 0) ob_end_flush();

    header('Content-Type: text/plain');
    header('Cache-Control: no-cache');
    ob_implicit_flush(true);

    $command = escapeshellcmd($_POST['command']);
    $timeout = 10; // seconds

    $descriptorspec = [
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];

    // time-limited passthru()
    $process = proc_open($command, $descriptorspec, $pipes);

    if (is_resource($process)) {
        $start = time();
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        while (true) {
            $output = stream_get_contents($pipes[1]);
            $error = stream_get_contents($pipes[2]);
            if ($output !== false && $output !== '') echo $output;
            if ($error !== false && $error !== '') echo $error;

            flush();

            $status = proc_get_status($process);
            if (!$status['running']) break;
            if ((time() - $start) > $timeout) {
                proc_terminate($process, 9);
                echo "\n-- Command timed out after {$timeout}s --\n";
                break;
            }
            usleep(100000); // 0.1s
        }
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);
    }
    flush();
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Streaming CLI Prototype</title>
    <link rel="stylesheet" href="./shell.css">
</head>
<body>
    <main class="container">
        <h1>Local Streaming CLI Prototype</h1>
        <p>Enter commands below to execute them on the local server. The output will stream in real-time.</p>

        <section>
            <h3 style="color: #48bb78;">Environment</h3>
            <p>
                <strong>Working Directory</strong> <?php echo htmlspecialchars(getcwd()); ?><br>
                <strong>PHP Version</strong> <?php echo htmlspecialchars(PHP_VERSION); ?><br>
                <strong>User</strong> <?= htmlspecialchars(get_current_user()); ?><br>
                <strong>Hostname</strong> <?= htmlspecialchars(gethostname()); ?><br>
                <strong>IP Address</strong>: <?= $ip = gethostbyname(gethostname()); ?>
            </p>
        </section>

        <section>
            <h3>Command History</h3>
            <ul id="command-history" class="history-container">
                <!-- History items will be dynamically inserted here -->
            </ul>
        </section>

        <!-- Terminal Output -->
        <div id="terminal-output" class="terminal" aria-live="polite">
            <section class="output-block system-block" aria-label="System message">
                <pre>
<span style="color: #a0aec0;">Welcome to the streaming PHP-powered CLI!</span>
<span style="color: #a0aec0;">Try running a command that takes time, like 'ping -c 5 google.com', to see the streaming in action.</span>
                </pre>
            </section>
        </div>

        <form id="command-form" class="input-form">
            <span class="prompt-symbol">$&gt;</span>
            <input type="text" id="command-input" class="input-field" autofocus autocomplete="off">
        </form>
    </main>

    <script>
        const form = document.getElementById('command-form')
        const input = document.getElementById('command-input')
        const outputLog = document.getElementById('terminal-output')
        const historyContainer = document.getElementById('command-history')
        let isFetching = false
        const COMMAND_HISTORY_KEY = 'cliCommandHistory'
        const MAX_HISTORY_LENGTH = 10
        let currentBlock = null

        function startNewOutputBlock(command) {
            currentBlock = document.createElement('section')
            currentBlock.className = 'output-block'
            currentBlock.setAttribute('aria-label', `Output for: ${command}`)
            currentBlock.innerHTML = `<header class="output-header"><span class="prompt-symbol">$&gt;</span> <span class="cmd">${command}</span></header><pre class="output-body"></pre>`
            outputLog.appendChild(currentBlock)
            outputLog.scrollTop = outputLog.scrollHeight
        }

        function appendOutput(chunk) {
            if (!currentBlock) return
            const body = currentBlock.querySelector('.output-body')
            body.textContent += chunk
            outputLog.scrollTop = outputLog.scrollHeight
        }

        function loadCommandHistory() {
            try {
                const history = JSON.parse(localStorage.getItem(COMMAND_HISTORY_KEY))
                return Array.isArray(history) ? history : []
            } catch (e) {
                console.error("Error loading command history from localStorage:", e)
                return []
            }
        }

        function saveCommandHistory(history) {
            try {
                localStorage.setItem(COMMAND_HISTORY_KEY, JSON.stringify(history))
            } catch (e) {
                console.error("Error saving command history to localStorage:", e)
            }
        }

        function renderCommandHistory() {
            const history = loadCommandHistory()
            historyContainer.innerHTML = ''
            history.forEach(command => {
                const commandElement = document.createElement('li')
                commandElement.textContent = command
                commandElement.className = 'history-item'
                commandElement.addEventListener('click', () => {
                    input.value = command
                    input.focus()
                })
                historyContainer.appendChild(commandElement)
            })
        }

        document.addEventListener('DOMContentLoaded', renderCommandHistory)

        form.addEventListener('submit', async (event) => {
            event.preventDefault()
            if (isFetching) {
                if (currentBlock) appendOutput('\n-- A command is already running. Please wait. --\n\n')
                return
            }
            const command = input.value.trim()
            if (!command) return
            input.value = ''
            startNewOutputBlock(command)
            try {
                isFetching = true
                const response = await fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                    method: 'POST',
                    body: `command=${encodeURIComponent(command)}`,
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`)
                const reader = response.body.getReader()
                const decoder = new TextDecoder()
                while (true) {
                    const { value, done } = await reader.read()
                    if (done) {
                        appendOutput('\n')
                        break
                    }
                    const chunk = decoder.decode(value)
                    appendOutput(chunk)
                }
                const history = loadCommandHistory()
                const newHistory = [command, ...history.filter(cmd => cmd !== command)]
                newHistory.splice(MAX_HISTORY_LENGTH)
                saveCommandHistory(newHistory)
                renderCommandHistory()
            } catch (error) {
                appendOutput(`\nError: Failed to execute command. ${error.message}\n`)
            } finally {
                isFetching = false
            }
        })
    </script>
</body>
</html>
