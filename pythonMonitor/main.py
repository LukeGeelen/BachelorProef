import subprocess
import json

def test(input, expectedOutput, stripTrailingNewlines=True):
    #A lot of programs will terminate with a newline
    #This newline might not be provided for in the checks.json
    #the stripTrailingNewlines will remove a trailing newline from both the output and the expected output
    command = ['./abba.out']

    p = subprocess.Popen(command, stdin=subprocess.PIPE, stdout=subprocess.PIPE, encoding='utf8')
    output = p.communicate(input=input)[0]
    if stripTrailingNewlines:
        output = output.rstrip()
        expectedOutput = expectedOutput.rstrip()
    passing = False
    if(output == expectedOutput):
        print("check passed")
        passing = True
    return (output, passing)


if __name__ == '__main__':
    checks_file = open('checks.json')
    checks = json.load(checks_file)
    jsonData = {}
    results = []
    for check in checks['tests']:
        result = {}
        output, outputMatch = test(check['input'], check['output'])
        result['input'] = check['input']
        result['expectedOutput'] = check['output']
        result['realOutput'] = output
        result['passing'] = outputMatch
        results.append(result)
    jsonData['results'] = results
    with open('results.json', 'w') as outfile:
        json.dump(jsonData, outfile)

