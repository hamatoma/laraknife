#! /usr/bin/python3

import sys
import re
import os.path

def usage(msg: str):
    print(f'''Usage: recolor.py COLOR
Example recolor green
+++ {msg}''')
    sys.exit(1)

def main(argv):
    color = None if len(argv) < 1 else argv[0]
    filename = None if color is None else f'public/css/{argv[0]}.css'
    if color is None:
        usage('+++ missing color')
    elif not os.path.exists(filename):
        usage(f'not found: {filename}')
    else:
        regexPurple = re.compile(r'purple colors: head: ([\da-fA-F]++) icons: ([\da-fA-F]+) shadow: ([\da-fA-F]+) panel: ([\da-fA-F]+) link: ([\da-fA-F]+) info: ([\da-fA-F]+)')
        #             '/* green colors: head: 1C191A        icons: 5D8967          shadow: 6b895dd2 panel: DDDFE0 link: 8CB094 info: F6C9CA */'
        regexNew = re.compile(r'colors: head: (\S+) icons: (\S+) shadow: (\S+) panel: (\S+) link: (\S+) info: (\S+)')
        with open(filename, 'r') as fp:
            lines = fp.read().split('\n')
        output = []
        sourceColors = None
        targetColors = None
        for line in lines:
            match = regexPurple.search(line)
            if match is not None:
                sourceColors = []
                for ix in range(6):
                    sourceColors.append(match.group(ix+1))
                output.append(line)
                print("purple definitions found")
                continue
            match = regexNew.search(line)
            if match is not None:
                targetColors = []
                for ix in range(6):
                    targetColors.append(match.group(ix+1))
                print("new definitions found")
                output.append(line)
                continue
            if sourceColors is None or targetColors is None:
                output.append(line)
                continue
            for ix in range(len(sourceColors)):
                source = sourceColors[ix]
                target = targetColors[ix]
                line = line.replace(source, target)
            output.append(line)
        if sourceColors is None or targetColors is None:
            print('+++ missing color definitions')
        else:
            with open(filename, "w") as fp:
                fp.write('\n'.join(output))
                fp.write('\n')
if __name__ == '__main__':
    main(['test'])
    #main(sys.argv[1:])