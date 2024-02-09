#! /usr/bin/python3
import os.path
import sys
import re

class ReplaceEngine:
    def __init__(self, separator: str) -> None:
        self._separator = separator
        self._replacements = {}
        self._filename = None
        self._lineNo = 0
        self._hits = 0
        self._countFiles = 0
        self._countHitFiles = 0
        self._countDirs = 0
        self._nodeRegExpr = None
        self.errors = 0
      
    def error(self, msg: str) -> None:
        if self._filename is None:
            print(f'+++ {msg}')
        else:
            print(f'+++ {self._filename}-{self._lineNo}: {msg}')
            
    def log(self, msg: str):
        print(msg)
    
    def logStatistics(self):
        self.log(f'dirs: {self._countDirs} files: {self._countHitFiles} from {self._countFiles} hits: {self._hits}')
        
    def oneFile(self, filename: str) -> None:
        self._countFiles += 1
        self._filename = filename
        lines = []
        hits = 0
        with open(filename, 'r') as fp:
            self._lineNo = 0
            for line in fp:
                self._lineNo += 1
                for pair in self._replacements.items():
                    hits2 = line.count(pair[0])
                    if hits2 > 0:
                        hits += hits2
                        self._hits += hits2
                        line = line.replace(pair[0], pair[1])
                lines.append(line)
        if hits > 0:
            self._countHitFiles += 1
            with open(filename, "w") as fp2:
                data = ''.join(lines)
                fp2.write(data)
            self.log(f'{filename}: {hits} replacement(s)')   
        
    def oneDirectory(self, path: str) -> None:
        self._countDirs += 1
        files = os.listdir(path)
        subdirs = []
        for node in files:
            full = f'{path}/{node}'
            if os.path.isdir(full):
                subdirs.append(node)
            elif self._nodeRegExpr is None or self._nodeRegExpr.match(node):
                self.oneFile(full)
        for node in subdirs:
             full = f'{path}/{node}'
             self.oneDirectory(full)            
            
    def readReplacements(self, filename: str) -> bool:
        rc = False
        self._filename = filename
        with open(filename, 'r') as fp:
            self._lineNo = 0
            for line in fp:
                self._lineNo += 1
                line = line.rstrip('\n\c')
                if line.strip() == '':
                    continue
                parts = line.split(self._separator)
                if len(parts) == 1:
                    self.error(f'missing separator ({self._separator}): {line}')
                elif len(parts) > 2:
                    self.error(f'too many separators: {line}')
                else:
                    self._replacements[parts[0]] = parts[1]
            rc = self.errors == 0
        self._filename = None
        return rc

    def setPattern(self, pattern: str) -> None:           
        self._nodeRegExpr = re.compile(pattern)
        
def usage(msg: str):
    print(f'''Usage: replacestrings <replacement-file> <file-or-directory> [<file-regex-pattern>]
  Does the replacements read from <replacement-file> in the specified <file-or-directory>..
  If <file-or-directory> is a directory all files matching the <file-regex-pattern> will be inspected.
  The search is recursive: subdirectories will be inspected too.
  Format of the <replacement-file>: each line contains a search string, a TAB as separator and the replacement.
<options>:
  --separator=<separator> or -s<separator>
    The separator in the <replacement-file> between search string and replacement.add()
    Default: TAB (tabulator)

Examples: 
replacestrings data/replacements.txt /ws/myproject '(.*\.[ch]pp|.*\.txt)$'
replacestrings data/replacements.txt --separator=, /ws/myproject/main.cpp
+++ {msg}
''')        
    sys.exit(1)   

def test():
    base = '/tmp/unittest'
    if not os.path.exists(base):
        os.mkdir(base)
    fnDemo = f'{base}/replacments.demo'
    fnData = f'{base}/replacments.data'
    with open(fnDemo, 'w') as fp:
        fp.write('''line 1
line 2
With a little help from my friends.
''')
        with open(fnData, 'w') as fp:
            fp.write('''line;Line
f;X
''')
    argv = ['--separator=;', fnData, base, '^.*\.demo$']
    return argv
    
def main(argv):
    if len(argv) == 0 or (len(argv) == 1 and argv[0] == '--test'):
        argv = test()
    if len(argv) < 2:
        usage('missing arguments')
    else:
        separator = '\t'
        while len(argv) > 0 and argv[0].startswith('-'):
            arg = argv[0]
            if arg.startswith('-s'):
                separator = arg[2:]
            elif arg.startswith('--separator='):
                separator = arg[12:]
            else:
                usage(f'unknown option: {arg}')
            argv = argv[1:]
        if separator == '':
            separator = '\t'
        engine = ReplaceEngine(separator)
        if not engine.readReplacements(argv[0]):
            usage(f'error in configuration file: {argv[0]}')
        else:
            source = argv[1]
            if not os.path.isdir(source):
                engine.oneFile(source)
            else:
                if len(argv) > 2:
                    engine.setPattern(argv[2])
                    engine.oneDirectory(source)
                    engine.logStatistics()

if __name__ == "__main__":
    main(sys.argv[1:])
