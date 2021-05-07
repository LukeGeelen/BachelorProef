import subprocess
import json
import sys
import requests
import urllib.parse

def charBycharComp(expected, got):
    for x,y in zip(expected, got):
        print("exp: " + x)
        print("got: " + y)
    return


def test(ip, id, input, expectedOutput, stripTrailingNewlines=True):
    #A lot of programs will terminate with a newline
    #This newline might not be provided for in the checks.json
    #the stripTrailingNewlines will remove a trailing newline from both the output and the expected output
    command = ['java', 'Main']

    p = subprocess.Popen(command, stdin=subprocess.PIPE, stdout=subprocess.PIPE, encoding='utf8')
    output = p.communicate(input=input)[0]
    if stripTrailingNewlines:
        output = output.rstrip()
        expectedOutput = expectedOutput.rstrip()
    passing = False
    if(output == expectedOutput):
        print("check passed")
        passing = True
    else:
        print("checkFailed")
        print("Expected: " + expectedOutput)
        print("got: " + output)
    handoffResult(ip, id, output, passing)
    return (output, passing)

def handoffResult(ip, id, output, passing):
    url = 'http://' + ip + '/processResult.php?type=result&id='
    url += str(id);
    url += '&output='
    url += urllib.parse.quote_plus(output)
    url += '&passing='
    url += str(passing)
    print(url)
    x = requests.get(url)
    print(x.text)

def getDockerInterfaceIp():
    command = ['./getDockerIP.sh']
    getIP = subprocess.Popen(command, stdin=subprocess.PIPE, stdout=subprocess.PIPE, encoding='utf8')
    IP = getIP.communicate(input='')[0]
    getIP.terminate();
    return IP.rstrip()

def compile(submissionId, ip):
    commandcom = ['make']
    compile = subprocess.Popen(commandcom, stdin=subprocess.PIPE, stdout=subprocess.PIPE, encoding='utf8')
    outputcom = compile.communicate(input='')[0]
    print("compile: " + outputcom)
    compile.terminate()
    print("<br><br>")

    url = 'http://' + ip + '/processResult.php?type=compiler&id='
    url += str(submissionId)
    url += '&compilerOutput='
    url += urllib.parse.quote_plus(outputcom)
    print(url)
    x=requests.get(url)
    print(x.text)
    print('<br>repCom above<br><br>')
    return 0

def runLinter(submissionId, ip):
    commandlint = ['echo', 'hi']
    linter = subprocess.Popen(commandlint, stdin=subprocess.PIPE, stdout=subprocess.PIPE, encoding='utf8')
    output = linter.communicate(input='')[0]
    print("lint: " + output)
    linter.terminate()
    print("<br><br>")

    url = 'http://' + ip + '/processResult.php?type=linter&id='
    url += str(submissionId)
    url += '&linterOutput='
    url += urllib.parse.quote_plus(output)
    print(url)
    x=requests.get(url)
    print(x.text)

    return 0
    
if __name__ == '__main__':
    checks_file = open('./checks.json')
    checks = json.load(checks_file)
    submission = checks['submission']
    
    ip = getDockerInterfaceIp()
    
    compile(submission, ip);
    
    runLinter(submission, ip)

   
    jsonData = {}
    results = []


    for check in checks['tests']:
        result = {}
        output, outputMatch = test(ip, check['id'], check['input'].replace("\\n", "\n"), check['output'].replace("\\n", "\n"))
        result['id'] = check['id']
        result['input'] = check['input']
        result['expectedOutput'] = check['output']
        result['realOutput'] = output
        result['passing'] = outputMatch
        results.append(result)
    jsonData['results'] = results
    print(jsonData)
