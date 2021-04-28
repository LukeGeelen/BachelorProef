import subprocess
import json
import sys
import requests

def charBycharComp(expected, got):
    for x,y in zip(expected, got):
        print("exp: " + x)
        print("got: " + y)
    return


def test(input, expectedOutput, stripTrailingNewlines=True):
    #A lot of programs will terminate with a newline
    #This newline might not be provided for in the checks.json
    #the stripTrailingNewlines will remove a trailing newline from both the output and the expected output
    command = ['./studentProgram']

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

    return (output, passing)

def handoffResult(resultString):
    url = 'http://localhost/processResult.php'
    x = requests.post(url, data=resultString)
    print(x.text)

if __name__ == '__main__':
    print(sys.version_info)
    checks_file = open('checks.json')
    checks = json.load(checks_file)
    jsonData = {}
    results = []
    for check in checks['tests']:
        result = {}
        output, outputMatch = test(check['input'], check['output'])
        result['id'] = check['id']
        result['input'] = check['input']
        result['expectedOutput'] = check['output']
        result['realOutput'] = output
        result['passing'] = outputMatch
        results.append(result)
    jsonData['results'] = results
    print(jsonData)
    handoffResult(jsonData)



