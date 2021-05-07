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
    command = ['./code/main.out']

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
    p.terminate()
    arguments= {'id':str(id), 'output':urllib.parse.quote_plus(output), 'passing':str(passing)}
    handoff(ip, 'result', arguments)
    #handoffResult(ip, id, output, passing)
    return (output, passing)
    
def handoff(ip, calltype, arguments, attempts=0):
    if(attempts >= 3):
        #todo log
        print("REQUEST FAILED")
        return -1;
    url = 'http://' + ip + '/processResult.php?type='
    url += calltype
    for argument, value in arguments.items():
        url += '&' +argument + '='
        url += str(value)
    print(url)
    try:
        x = requests.get(url)
    except requests.exceptions.RequestException as e: 
        handoff(ip, calltype, arguments, (attempts+1))
    
    print(x.text)
    if(x.text.rstrip() == "OK"):
        return 0;
    else:
        handoff(ip, calltype, arguments, (attempts+1))
   
    
def getDockerInterfaceIp():
    command = ['./code/getDockerIP.sh']
    getIP = subprocess.Popen(command, stdin=subprocess.PIPE, stdout=subprocess.PIPE, encoding='utf8')
    IP = getIP.communicate(input='')[0]
    getIP.terminate();
    return IP.rstrip()

def compile(submissionId):
    commandcom = 'make -C ./code'

    compile = subprocess.Popen(commandcom, shell=True,stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE,encoding='utf8')
    out , err = compile.communicate(input='')
    print("compile: " + out)
    compile.terminate()

    
    outputcom = out + "\n" + err
    arguments = {'id':str(submissionId), 'compilerOutput':urllib.parse.quote_plus(outputcom)}
    handoff(ip, 'compiler', arguments)
    return 0


def runLinter(submissionId):
    commandlint = ['scan-build', 'make', '-C', './code']
    linter = subprocess.Popen(commandlint, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, encoding='utf8')
    output,err = linter.communicate(input='')
    print("lint: " + err)
    linter.terminate()
    
    arguments = {'id':str(submissionId), 'linterOutput':urllib.parse.quote_plus(output + '\n' + err)}
    handoff(ip, 'linter', arguments)
    
    return 0

if __name__ == '__main__':

    ip = getDockerInterfaceIp()

    checks_file = open('./code/checks.json')
    checks = json.load(checks_file)
    submission = checks['submission']
    print("sumbission" + str(submission))
    print("<br><br>")

    compile(submission)

    runLinter(submission)

    jsonData = {}
    results = [] #todo remove json output, not needed
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
    print("<br/> inline output can be deleted when running async <br/>")
    print(jsonData)
