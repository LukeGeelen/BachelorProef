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


def test(id, input, expectedOutput, stripTrailingNewlines=True):
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
    handoffResult(id, output, passing)
    return (output, passing)

def handoffResult(id, output, passing):
    url = 'http://127.0.0.1/processResult.php?id='
    url += str(id);
    url += '&output='
    url += urllib.parse.quote_plus(output)
    url += '&passing='
    url += str(passing)
    print(url)
    x = requests.get(url)
    print(x.text)

if __name__ == '__main__':
    print(sys.version_info)
    checks_file = open('./code/checks.json')
    checks = json.load(checks_file)
    jsonData = {}
    results = []
    for check in checks['tests']:
        result = {}
        output, outputMatch = test(check['id'], check['input'].replace("\\n", "\n"), check['output'].replace("\\n", "\n"))
        result['id'] = check['id']
        result['input'] = check['input']
        result['expectedOutput'] = check['output']
        result['realOutput'] = output
        result['passing'] = outputMatch
        results.append(result)
    jsonData['results'] = results
    print(jsonData)
